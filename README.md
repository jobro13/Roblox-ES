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



## In-depth

### Get packages
Use your package manager to install the packages listed above. For debian and ubuntu, you use apt-get.

### MySQL setup
Login to mysql: `mysql -u root -p`. -u is the user switch. -p is a password switch

Create a new user inside the mysql prompt:

`CREATE USER 'roblox'@'localhost' IDENTIFIED BY 'password'` 

Where roblox is the username you use for Roblox-ES and password the password.

Create a roblox database (also in mysql):

`CREATE DATABASE ROBLOX;`

Grant privileges to the user:

`GRANT ALL PRIVILEGES ON ROBLOX . * TO 'roblox'@'localhost';`

Make sure that 'roblox' is the username you want to use here.

Now revoke the 'grant permission' and 'drop' (delete) permissions. This is for safety.

`REVOKE DROP ON ROBLOX . * FROM 'roblox'@'localhost';`

`REVOKE GRANT PRIVILEGES ON ROBLOX . * FROM 'roblox'@'localhost';`

Now quit mysql. Run setup.sh. This setups the initial server table.

### Setup apache and php

Find the apache configuration and the sites-enabled. Change the DocumentRoot in this configuration to the ./site directory here. 

The last thing to do is to create a file called db.php inside any of php's include dirs. You can find the include_dirs by create a file inside site, which has the following contents;
`<?php	
	phpinfo()
?>`

If you restart apache (apachectl restart, maybe needs root, so sudo) and go to this page, you will get the php configuration which also shows the include_dirs. You can also edit the configuration file (location is also on this page) and edit the include_dir locations there.
Now create this db.php file. This are the contents:

`<?php
	$db_user = "roblox";
	$db_passwd = "password";
?>`

The pages will read the user and password from this directory. Like this, it is not publicly exposed, if it resides on a safe place.

Now restart apache. (apachectl restart)

Insert the model inside roblox. Read the ReadME and configure it. Try starting a test server on your local LAN with a test script in it. Now check if the data actually gets added to the site.

You can then upload it to roblox. If external servers cannot reach your server, you have to port forward your apache http port (for this job) to a public port (80 is default). To do this, refer to your router manual.

