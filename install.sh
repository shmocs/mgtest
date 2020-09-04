#!/bin/bash

cp .env.example .env
docker-compose up -d
docker-compose ps

docker-compose exec mysql mysql -uroot -proot --execute="CREATE DATABASE IF NOT EXISTS mgtest;"
