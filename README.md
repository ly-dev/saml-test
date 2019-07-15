# About The Ricard Back-end
Ricardo back-end is a web application based on [the Laravel framework](https://laravel.com/docs/5.2/). It provides APIs for the Ricardo App.

The application is written in PHP, [Sass](https://sass-lang.com/) (transpiling into CSS) and Javascript.

# Set Up The Development Environment
To work on the code, you need to set up the development environment. Here, we assume you are working on a [Ubuntu 18.04](http://releases.ubuntu.com/18.04/) desktop. For other operation systems, please adapt the below instructions accordingly.

## Prerequisites

### LAMP Stack
First, we need Apache, MySQL, and PHP installed on the Linux. You may refer to a document from [here](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-ubuntu-18-04).

### Node.js
Second, we need [Node.js](https://nodejs.org). It is highly recommend to use Node.js v8.x. You may get the instructions from [here](https://github.com/nodesource/distributions/blob/master/README.md).

### Visual Studio Code
We recommend to use the [Visual Studio Code](https://code.visualstudio.com/).

To work with PHP, it is suggested to install below extensions:
* PHP IntelliSense by Felix Becker
* PHP Debug by Felix Becker
* Debugger for Chrome by Microsoft


## Working On The Back-end Code
We assume you've created a folder nameed "workspace" under your home directory on the Ubuntu desktop. Otherwise, please create the folder with below commands:
```
cd ~
mkdir workspace
```

### Clone The Source Code
You may get the source code from the Git repository and switch to folder www/protected underder the project folder created by the Git clone.
```
cd ~/workspace
git clone https://github.com/xsinclair/ricardo.git
cd ricardo/www/protected
```

### Install The Dependencies
Use npm to install all the dependancies of the project.
```
composer install
npm install
```

### Create Database
Us mysql to create database
```
mysql -u root -p -e "CREATE DATABASE ricardo"
```

### Generate .env file
We need to generate .env used to config the application.
```
cp default.env .env
```

Update the .env file for proper APP_URL and DB related configurations. Assume, the web application will run on the localhost Apache server under the sub-folder named " ricardo" (we'll set up this).
```
APP_URL=http://localhost/ricardo

DB_HOST=localhost
DB_DATABASE=ricardo
DB_USERNAME=root
DB_PASSWORD=#your root password#
```

### Initialise The Database
Use Laravel commandline to initialise the database and put in some tester users.
```
php artisan migrate
php artisan db:seed
php artisan db:seed --class=TestSeeder
```

You may refer to the file database/seeds/TestSeeder.php and get tester users information, e.g. email and password. Feel free to adapt the file to use or add your own test users.

### Compile The Assets
Use gulp to compile the assets
```
node_modules/.bin/gulp
```

BTW, you could use gulp to dynamically compile the assets during development.
```
node_modules/.bin/gulp watch
```

### Update The File Access Permission
Assume, your login as user "dev" on Ubuntu. If not, please adapt below commands with your user name.
```
sudo chown -R dev:www-data  /home/dev/workspace/ricardo
sudo chmod -R ug+w /home/dev/workspace/ricardo/www/protected/storage
sudo chmod -R ug+w /home/dev/workspace/ricardo/www/protected/bootstrap/cache
```

### Create Symbol Link In The Web Root
To make the application accessible via Apache Web Server, a symbol link needs creating in the Web root.
```
sudo ln -s /home/dev/workspace/ricardo/www /var/www/ricardo
```

This will make the application accessible on URL http://localhost/ricardo.

## Enable The Test Cases
The back-end is developed with automation test cases (api and acceptance). The test cases are in tests/api and tests/acceptance. It is based on the [Codeception](https://codeception.com/).

 To run the test cases, we need proper set up.

### Prepare The Codeception Environment
The environment files are in tests/_envs. It looks like
```
modules:
    config:
        REST:
            url: http://localhost/ricardo
        WebDriver:
            url: http://localhost/ricardo
            host: localhost
            port: 4444
            browser: firefox
            window_size: 1600x900
```

Try to create a file with above settings and name it as "local.yml". Pay attention to the url, which is the url of the Ricardo web application. 

Read more about the codeception environment from [here](https://codeception.com/docs/07-AdvancedUsage#Environments).

### Run API Test Cases
With above settings, you may run the API test cases
```
vendor/bin/codecept build
vendor/bin/codecept run api --env local
```

### Run Acceptance Test Cases
To run acceptance test cases, you need a selenium server. You need to download the [Selenium](https://www.seleniumhq.org/download/). It is a Java package, e.g. selenium-server-standalone-3.141.59.jar.

Maybe, you need to install Java. If so, we recommend to install Oracle Java version 8. Refer to [this doument](https://linuxconfig.org/how-to-install-java-on-ubuntu-18-04-bionic-beaver-linux) for instructions.

Then, run the server in another terminal
```
selenium-server-standalone-3.141.59.jar
```

Now, you could run the acceptance test cases
```
vendor/bin/codecept run acceptance --env local
```
