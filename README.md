# Style Guide

Style Guide is a web application that lets you easily construct a style guide and a mood board. It is intended to be used by designers and developers during the course of designing and then developing an application. The goal was to give a simpler solution to creating and maintaining a style guide, which is often done in Photoshop and which can be quite cumbersome.

## Features

* User authentication
* Mood board for collecting design inspiration, with ability to add comments
* Style guide for documenting color schemes, typography, icons, and other design elements

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See [Deployment](https://github.com/bradwilder/style-guide#Deployment) for notes on how to deploy the project on a live system.

### Prerequisites

* [Apache 2](https://httpd.apache.org/)
* [MySQL 5.6.35](https://www.mysql.com/)
* [PHP 7.1.1](http://www.php.net/)
* [npm](https://www.npmjs.com/)
* [Composer](https://getcomposer.org/)
* An email account that can be used for sending registration emails
* An account on [twilio.com](https://www.twilio.com/) to use for sending registration sms messages

### Installing

1. Clone the repository from GitHub:
```
git clone git://github.com/bradwilder/style-guide.git
```

2. Navigate into the cloned repository directory:
```
cd style-guide
```

3. Install the npm dependencies:
```
npm install
```

4. Install the composer dependencies:
```
composer install
```

5. Copy the 4 .ini files from the `scripts` directory to the project root directory:
```
cp ./scripts/config*.ini .
```

6. Configure the 4 .ini files that were just copied to the root directory

7. Start the MySQL database so the schema can run. If you use MAMP for development purposes, there are gulp tasks that will start and stop the MAMP server:
```
// Starting the server; the mampDev command will point to the `app` directory, the mampDist command will point to the `docs` directory:
gulp mampDev
gulp mampDist

// Stopping the server
gulp mampStop
```

8. Run the setup scripts:
```
cd scripts
./setupAllServers.sh 1
cd ..
```
Note: After running this script, you can delete all but config.ini from the root directory. You only need them again if you plan to re-run the setup scripts (e.g., to replace a broken schema).

9. Build the application:
```
gulp build
```

10. Run the watch task to start BrowserSync:
```
gulp watch
```

#### Installing with pre-installed style guide

To setup the application with a style guide pre-populated for testing, follow the [Installing](https://github.com/bradwilder/style-guide#Installing) instructions, but replace step 8 with this:
```
cd scripts/viewer
./setup.sh 1
cd ../..
```

## Running the tests

The automated tests can be run with this command, assuming the database is running:
```
npm run testServer
```

## Deployment

Deploying to a live server is similar to the setup for development purposes. These instructions will assume you're using a separate database and web server, but that isn't a requirement.

1. On both the database and web servers, repeat steps 1-7 from the [Installing](https://github.com/bradwilder/style-guide#Installing) section

2. On the web server, run the setup scripts:
```
cd scripts/webServer
./setupWebServer.sh
cd ../..
```

3. On the database server, run the setup scripts:
```
cd scripts/db
./setupDBServer.sh
cd ../..
```

4. On the web server, repeat step 9 from the [Installing](https://github.com/bradwilder/style-guide#Installing) section

5. Run this command to launch BrowserSync:
```
gulp previewDist
```

## Authors

* **Brad Wilder** - [bradwilder](https://github.com/bradwilder)

## License

This project is licensed under the MIT License - see the [LICENSE](https://github.com/bradwilder/style-guide/blob/master/LICENSE) file for details

## Acknowledgments

* The PHPAuth library was originally forked from [here](https://github.com/PHPAuth/PHPAuth)
