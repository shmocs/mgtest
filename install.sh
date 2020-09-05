#!/bin/bash

cp .env.example .env
docker-compose up -d
docker-compose ps

# allow mysql to boot up
sleep 3

msg="\n============ Create database =============="
echo -e "$msg"
docker-compose exec mysql mysql -uroot -proot --execute="CREATE DATABASE IF NOT EXISTS mgtest;"
