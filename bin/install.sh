#!/bin/bash
docker-composer run php composer install
docker-compose run php bin/console doctrine:database:create
docker-compose run php bin/console server:run

