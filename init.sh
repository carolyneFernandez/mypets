#!/bin/bash
export UID
docker-compose build
docker network create isolated_mypets --internal
docker-compose up -d
docker exec -it -u symfony mypets_web bash
