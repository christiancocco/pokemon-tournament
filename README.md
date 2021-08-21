# Pokemon Tournament
***

## Table of Contents
1. [General Info](#general-info)
2. [Technologies](#technologies)
3. [Installation](#installation)
4. [Usage](#usage)
5. [License](#license)

## General Info

"Pokemon Tournament" is a simple application to create our Pokemon Team for the next Pokemon Tournament.


## Technologies

This application is dockerized and you need a docker serve to run it.

## Installation

For build images, create a container and run it
```bash
docker-compose up -d
```
To creare a database on pokemon_tournament_app container
```bash
docker exec -it pokemon_tournament_app symfony console doctrine:migration:migrate --no-interaction
```

## Usage
Following the link to access to the application and phpMyAdmin to see the data

- http://127.0.0.1:8089 : To access phpMyAdmin
- http://127.0.0.1:2520 : To access the Pokemon Tournament application

## License
[MIT](https://choosealicense.com/licenses/mit/)