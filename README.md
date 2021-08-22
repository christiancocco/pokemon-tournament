# Pokemon Tournament
<p align="center"><img src="https://raw.githubusercontent.com/christiancocco/pokemon-tournament/main/assets/images/Pokemon_logo.png" width="400"></p>

## Table of Contents
1. [General Info](#general-info)
2. [Technologies](#technologies)
3. [Installation](#installation)
4. [Usage](#usage)
5. [License](#license)

## General Info

"Pokemon Tournament" is a simple application to create our Pokemon Team for the next Pokemon Tournament.<br>
With this application you can view pokemon team's list, create, view and edit team detail.<br>

## Technologies

Pokemon Tournament is a Symfony web app application using Vue.js frontend framework and Bootstrap 5.

#### Server Side Framework
Symfony: 5.3.*<br>

#### Frontend Framework
Vue.js: 2.6.14<br>
Bootstrap: 5

This application is dockerized and you need a docker serve to run it.

## Installation

For build images, create a container and run it
```bash
$ docker-compose up -d
```

When container starts, some commands will be run:<br>
<ul>
<li>composer install</li>
<li>npm install</li>
<li>npm run build</li>
<li>symfony console doctrine:migration:migrate --no-interaction</li>
</ul>
For this reason the container will be ready after a few seconds.

## Usage
Following the link to access to the application and phpMyAdmin to see the data

- http://127.0.0.1:8089 : To access phpMyAdmin
- http://127.0.0.1:2520 : To access the Pokemon Tournament application

## License
[MIT](https://choosealicense.com/licenses/mit/)