# fundme
A fund raising platform for users, where they can post new project and can raise funds for those projects. [NYU CS 6083 (Database Systems) - Final Project]


FundMe is a place, where all kinds of people can wish to discuss an idea for a project that they have in mind. This platform allows the users to create a project and raise funds for that project. Also, including these, other users can view, like, comment, and pledge money for the project. The owners of the project can also post updates about the project, the updates on the activity of the project members, may be some images, or videos as well. 

The users can also choose to follow and unfollow others users as they like. They can post comments on these projects, where they can discuss about the project. 

Once the project has been successfully completed, the users who pledged money for the project, can also rate the project. 

The process to get started with the application are:
1. Download the supported version of xampp and mysql on your desktop.
2. Download the fundme folder. Even the database related files are included in the same folder.
3. Copy this folder in /Applications/XAMPP/xamppfiles/htdocs/ .  -> Only for MAC users. For windows, paste this folder in htdocs directory of your base location of server. 
4. Open xampp database using URL: "localhost/phpmyadmin/"
5. Create a database named "fundraise".
6. Copy the create.sql, after_create.sql, insert_queries.sql script files sequentially in fundme/db/ Folder to SQL script editor of "fundraise" database and click GO at the bottom right of the page.
7. Open URL: "localhost/fundme" and you will see the first page of application.
8. You are good to go now!
