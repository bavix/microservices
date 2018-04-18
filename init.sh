#!/usr/bin/env bash

if [[ $(docker network ls -f name=microservices -q) = '' ]]; then
    docker network create microservices --subnet=172.16.140.0/24
fi

WORK_DIR=$(cd $(dirname $0) && pwd)
cd ${WORK_DIR}
cd containers

containers=(memcached gearman app gateway store)

for i in "${containers[@]}"; do
    cd $i
    docker-compose up -d --force-recreate --remove-orphans
    cd ..
done;
