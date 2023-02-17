# Vacation Manager
Vacation manager for organisations, with PHP and Vue3. Clean Architecture with DDD approach.

## Repo Directories
* `app` | PHP 7.4 APP
    * `schema` | MySQL Schema
    * `src` | API Source code
* `web` | Vue 3 Development source
  * `src` | Vue source code
* `public`  | Public root directory (e.g. www.domain.com/)
    * `/`   | Vue build files
    * `api` | Initialize PHP API

## API Layers
* `Core` (Core Layer)
* `Application` (Business Layer)
* `Infrastructure` (DI Layer)
* `Api` (Controller Layer)
* `Gateways` (External Layer)

## EER Diagram
![EER Diagram](app/schema/vacation.png?raw=true "EER Diagram")

## Installation
* Clone repository 
* Create a new mysql database and import `app/schema/vacation.sql`
* Update `app/.env` file with MySQL credentials, Domain URL & SMTP credentials
* From your console execute `composer install` on `app/` dir 
* Make sure your webserver is configured to serve files from `public` directory

## Requirements
* PHP > 7.4. 
* MySQL > 5.6.
* Composer
* SMTP || PHP Sendmail

## TODO (Order matters)
* Add migration script 
* Add JWT authentication 
* Log Actions on application status change
* Show logged actions on admin for every application
* Delete users & applications
* Current loggedin user to update info
* Create a super admin page where he can manage the application options
* Show calendar on admin with approved application dates
