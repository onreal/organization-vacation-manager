# Vacation Manager
Vacation manager for organisations, with PHP and Vue3. Clean Architecture with DDD approach.

## How it works
Employees create application for vacation leave, 
Managers are notified with email and click accept or reject from the email body. Employee 
is notified with email about the manager decision.

## Repo structure
* `app` _PHP 7.4 Backend_
    * `schema` _MySQL Schema_
    * `src` _API Source code_
* `web` _Vue 3 Development source_
    * `src` _Vue source code_
* `public`  _Public root directory (e.g. www.domain.com/)_
    * `/`   _Vue build files_
    * `api` _Initialize PHP API_

## [Backend structure](https://github.com/onreal/vacation-manager/tree/main/app/src)
* `Core/` (Core Layer)
* `Application/` (Business Layer)
* `Infrastructure/` (DI Layer)
* `Api/` (Controller Layer)
* `Gateways/` (External Layer)

## [Frontend structure](https://github.com/onreal/vacation-manager/tree/main/web/src)
* `assets/` (CSS & images)
* `components/` (UI Components & mixins)
* `routes/` (Vue routes)
* `store/` (Vue composition API store)

## EER Diagram
![EER Diagram](app/schema/vacation.png?raw=true "EER Diagram")

## Development Notes
* For Vue 3 front end development [check this readme.txt](https://github.com/onreal/vacation-manager/tree/main/web)
* [Vite Configuration](https://github.com/onreal/vacation-manager/blob/main/web/vite.config.js)  for web app builds directly into the public directory 
* Make sure your webserver is configured to serve files from `public` directory
* I use [nanoninja docker composer](https://github.com/nanoninja/docker-nginx-php-mysql) for my development needs

## Installation
1. Clone repository
2. Create a new mysql database and import `app/schema/vacation.sql`
3. Update `app/.env` with MySQL credentials, frontend URL & SMTP credentials
4. Update `web/.env` with the backend API url `VITE_API_URL`
5. From your console execute `npm install && npm run build` on `web/` dir
6. Copy `public/` directory files on your webserver public root
7. From your console execute `composer install` on `app/` dir
8. Copy `app/` outside your webserver public root (one level)
9. Visit your domain eg. https://localhost, you should see the login page

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
* NPM 8.19.
* Node 18.12.
* SMTP || PHP Sendmail

## TODO
* Add migration script
* Add tests (Codeception)
* Add API Documentation (Swagger)
* Add JWT authentication 
* Log Actions on application status change
* Show logged actions on admin for every application
* Delete users & applications
* Current loggedin user to update info
* Create a super admin page where we can manage the application options
* Show calendar on admin with approved application dates
