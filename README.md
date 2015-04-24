# RobloxEvents
Send HTTP events to a server and show them there. Stores data in a MySQL database.

## Installation
Install the following packages: apache2, mysql-client, mysql-server, php5, php5-mysql 
Login to mysql (mysql -u root -p), provide the password, and run 'create database ROBLOX;'
Run ./setup.sh. This setups the "Server" table inside the ROBLOX database.

Now, to add the user which will update the database:

*	Login to MySQL client as root
*	Create a new user, with password
*	GRANT all privileges, except "grant option" and "drop" to this user.
*	Add a file into the php include_path which is called db.php which sets db_user and db_password.
*	Setup apache to have a documentroot which points to site

*	Open roblox and input the model. Read it's README and setup the right options.
*	(Re)start apache.
*	Fun.
