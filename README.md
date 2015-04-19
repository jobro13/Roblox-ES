# RobloxEvents
Send HTTP events to a server and show them there. Stores data in a MySQL database.

## Installation
Install the following packages: apache2, mysql-client, mysql-server, php5, php5-mysql 
Login to mysql (mysql -u root -p), provide the password, and run 'create database ROBLOX;'
Run ./setup.sh PWD where PWD is the password of the mysql root user.
Above script creates a server table.
The next thing to do: 

* Login to mysql as root
* Create a new user with password
* GRANT all privileges, except "grant option" and "drop" to this user
* add a file in php include_path called 'db.php' which sets db_user and db_password 
