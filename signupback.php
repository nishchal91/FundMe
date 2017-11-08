<?php
//require 'navbar.php';
    require 'dbconn.php';


    if (isset($_POST['fname']) and isset($_POST['lname']) and isset($_POST['email']) and
        isset($_POST['address_line_1']) and isset($_POST['address_line_2']) and isset($_POST['city']) and
        isset($_POST['state']) and isset($_POST['country']) and isset($_POST['zip']) and isset($_POST['phone']) and
        isset($_POST['credit_card_number']) and isset($_POST['cvv']) and isset($_POST['month']) and isset($_POST['year'])){

        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['passwordConfirm'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $address_line_1 = $_POST['address_line_1'];
        $address_line_2 = $_POST['address_line_2'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $zip = $_POST['zip'];
        $phone = $_POST['phone'];

        $credit_card_number = $_POST['credit_card_number'];
        $cvv = $_POST['cvv'];
        $month = $_POST['month'];
        $year = $_POST['year'];



        if($password === $password2){
            if(trim($address_line_2)==''){
                $sql = "INSERT into user(email_id,password,first_name, last_name, address_line_1, address_line_2, city, state, country, zipcode, phone)
                values('" . $email . "','" . $password . "','" . $fname . "','" . $lname . "','" . $address_line_1 . "', NULL ,'". $city ."','" . $state . "','" . $country . "','" . $zip . "','" . $phone . "')";
            }else{
                $sql = "INSERT into user(email_id,password,first_name, last_name, address_line_1, address_line_2, city, state, country, zipcode, phone)
                values('" . $email . "','" . $password . "','" . $fname . "','" . $lname . "','" . $address_line_1 . "','" . $address_line_2 . "','". $city ."','" . $state . "','" . $country . "','" . $zip . "','" . $phone . "')";
            }

            echo $sql;
            if ($conn->query($sql)  === TRUE) {

                $credit_card_insert = "insert into CREDIT_CARD VALUES ('" .$credit_card_number . "','" . $cvv . "','" . $month . "','" . $year . "','" . $email . "');";
                if($conn->query($credit_card_insert) === TRUE){
                    echo "Success Record Inserted";
                    //session_destroy();
                    session_start();
                    $_SESSION['email'] = $_POST['email'];
                    header('location: homepage.php');
                }
            }else{
                ?>
                <script>
                    alert("Registration not successful.");
                    window.location.href = 'index.php';
                </script>
                <?php
            }
        }else {
            ?>
            <script>
                alert("The passwords do not match!")
                window.location.href = 'index.php';
            </script>
            <?php
        }
        # code...
    } else {
        ?>
        <script>
            alert("The required fields are not valid")
            window.location.href = 'index.php';
        </script>
        <?php
    }
?>

