#!/bin/bash
docker-composer run php composer install
docker-compose run php bin/console doctrine:database:create
docker-compose run php bin/console doc:mig:mig
docker-compose run php bin/console server:run


# http://127.0.0.1:8000/api/