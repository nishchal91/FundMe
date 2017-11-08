CREATE
VIEW `campaign_status` AS
SELECT
`f`.`PROJECT_ID` AS `PROJECT_ID`,
SUM(`f`.`PLEDGED_MONEY`) AS `total_pledged_money`,
`c`.`FUND_START_DATE` AS `FUND_START_DATE`,
`c`.`FUND_END_DATE` AS `FUND_END_DATE`,
`c`.`MIN_FUND_GOAL` AS `MIN_FUND_GOAL`,
`c`.`MAX_FUND_GOAL` AS `MAX_FUND_GOAL`,
`c`.`STATUS` AS `STATUS`
FROM
(`funds` `f`
JOIN `project_campaign` `c` ON ((`c`.`PROJECT_ID` = `f`.`PROJECT_ID`)))
GROUP BY `f`.`PROJECT_ID`;

-- 

CREATE DEFINER=`root`@`localhost` TRIGGER funds_AFTER_INSERT AFTER INSERT  ON funds FOR EACH ROW
BEGIN

if exists (select * from campaign_status where project_id=new.project_id and total_pledged_money>=MAX_FUND_GOAL and
campaign_status.STATUS='IN_PROGRESS' and FUND_END_DATE>=now() AND FUND_START_DATE<=NOW()
)
then
Update project_campaign  set project_campaign.STATUS ='COMPLETED' where project_campaign.PROJECT_ID=new.PROJECT_ID ;
update project set project.STATUS='STARTED' where project.PROJECT_ID=new.PROJECT_ID;

END if;
END;

--

CREATE DEFINER=`root`@`localhost` PROCEDURE
`check_and_update_project_campaign_status`()
BEGIN
UPDATE project_campaign SET STATUS='COMPLETED' WHERE PROJECT_ID IN (
SELECT PROJECT_ID FROM CAMPAIGN_STATUS WHERE NOW()>=FUND_END_DATE AND
MIN_FUND_GOAL <= TOTAL_PLEDGED_MONEY AND STATUS='IN_PROGRESS');

UPDATE project_campaign SET STATUS='FAILED' WHERE PROJECT_ID IN (
SELECT PROJECT_ID FROM CAMPAIGN_STATUS WHERE NOW()>=FUND_END_DATE AND
MIN_FUND_GOAL > TOTAL_PLEDGED_MONEY AND STATUS='IN_PROGRESS');

create temporary table if not exists project_ids_to_be_updated as (SELECT DISTINCT
a.PROJECT_ID FROM project_campaign a JOIN funds as b ON a.PROJECT_ID=b.PROJECT_ID
WHERE a.STATUS='COMPLETED' AND b.TRANSACTION_DATETIME IS NULL);

UPDATE funds SET TRANSACTION_DATETIME=NOW() WHERE PROJECT_ID IN (select * from
project_ids_to_be_updated);

UPDATE PROJECT SET STATUS='STARTED' WHERE PROJECT_ID IN (
SELECT PROJECT_ID FROM CAMPAIGN_STATUS WHERE NOW()>=FUND_END_DATE AND
MIN_FUND_GOAL <= TOTAL_PLEDGED_MONEY AND STATUS='IN_PROGRESS');

UPDATE PROJECT SET STATUS='FAILED' WHERE PROJECT_ID IN (
SELECT PROJECT_ID FROM CAMPAIGN_STATUS WHERE NOW()>=FUND_END_DATE AND
MIN_FUND_GOAL > TOTAL_PLEDGED_MONEY AND STATUS='IN_PROGRESS');

create temporary table if not exists project_ids_to_be_updated_as_started as (
  SELECT DISTINCT PROJECT_CAMPAIGN.PROJECT_ID FROM PROJECT_CAMPAIGN
    JOIN PROJECT ON PROJECT_CAMPAIGN.PROJECT_ID = PROJECT.PROJECT_ID
  WHERE PROJECT_CAMPAIGN.STATUS='COMPLETED'
  AND PROJECT.STATUS != 'COMPLETED'
);

UPDATE PROJECT SET STATUS='STARTED' WHERE NOW()>=START_DATE and PROJECT_ID IN (select * from
project_ids_to_be_updated_as_started);

create temporary table if not exists project_ids_to_be_updated_as_failed as (
  SELECT DISTINCT PROJECT_CAMPAIGN.PROJECT_ID FROM PROJECT_CAMPAIGN
  WHERE PROJECT_CAMPAIGN.STATUS='FAILED'
);

UPDATE PROJECT SET STATUS='FAILED' WHERE PROJECT_ID IN (select * from
project_ids_to_be_updated_as_failed);

UPDATE PROJECT_CAMPAIGN, PROJECT
    SET PROJECT_CAMPAIGN.STATUS = 'COMPLETED', PROJECT.STATUS = 'STARTED'
WHERE
  PROJECT.PROJECT_ID = PROJECT_CAMPAIGN.PROJECT_ID AND
  PROJECT.PROJECT_ID IN (
  SELECT PROJECT.PROJECT_ID FROM PROJECT
  JOIN CAMPAIGN_STATUS ON PROJECT.PROJECT_ID = CAMPAIGN_STATUS.PROJECT_ID
  WHERE TOTAL_PLEDGED_MONEY >= MAX_FUND_GOAL AND CAMPAIGN_STATUS.STATUS = 'IN_PROGRESS'
);

END;

--

SET GLOBAL event_scheduler = ON;

--

delimiter |
create event update_project_and_campaign_status
on schedule every 1 minute
do
begin
CALL check_and_update_project_campaign_status();
end |
delimiter;

--

