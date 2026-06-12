#!/bin/bash

echo "create schema in database"
php bin/console doctrine:database:create

echo "migrate entities"
php bin/console make:migration
php bin/console doctrine:migrations:migrate

echo "start symfony"
symfony server:stop
symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async -vv
symfony server:start --no-tls -d
symfony open:local  
symfony server:log