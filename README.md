# Vacation Manager
Vacation manager for organisations, with PHP and Vue3. Clean Architecture with DDD approach.

## How it works
Employees create applications for vacation leave, 
Managers are notified with email and click accept or reject from the email body. Employee 
is notified with email about the manager decision.

## Repo Directories
* `app` _PHP 7.4 APP_
    * `schema` _MySQL Schema_
    * `src` _API Source code_
* `web` _Vue 3 Development source_
  * `src` _Vue source code_
* `public`  _Public root directory (e.g. www.domain.com/)_
    * `/`   _Vue build files_
    * `api` _Initialize PHP API_

## API Layers
* `Core` (Core Layer)
* `Application` (Business Layer)
* `Infrastructure` (DI Layer)
* `Api` (Controller Layer)
* `Gateways` (External Layer)

## EER Diagram
![EER Diagram](app/schema/vacation.png?raw=true "EER Diagram")

## Development Notes
* For Vue 3 front end development [check this readme.txt](https://github.com/onreal/vacation-manager/tree/main/web)
* [Vite Configuration](https://github.com/onreal/vacation-manager/blob/main/web/vite.config.js)  for web app builds directly into the public directory 
* Make sure your webserver is configured to serve files from `public` directory

## Installation
* Clone repository 
* Create a new mysql database and import `app/schema/vacation.sql`
* Update `app/.env` file with MySQL credentials, Domain URL & SMTP credentials
* `public/` directory files should be copied on your webserver public root
* `app/` directory should be copied outside your webserver public root
* From your console execute `composer install` on `app/` dir
* Visit your domain eg. https://localhost, you should see the login page

## Default Users
There are two default users with the provided MySQL schema.

    Admin
    login: margariti@hotmail.com
    password: P@ssw0rd$
---
    Employee
    login: ioannis.joedopoulos@hotmail.com 
    password: P@ssw0rd$

## Requirements
* PHP > 7.4. 
* MySQL > 5.6.
* Composer
* SMTP || PHP Sendmail

## TODO (Order matters)
* Add migration script
* Add API Documentation (Swagger)
* Add JWT authentication 
* Log Actions on application status change
* Show logged actions on admin for every application
* Delete users & applications
* Current loggedin user to update info
* Create a super admin page where we can manage the application options
* Show calendar on admin with approved application dates
