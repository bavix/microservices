#!/bin/bash
envsubst '$${NGINX_HOST}' < /etc/nginx/docker.conf > /etc/nginx/conf.d/default.conf
nginx -g 'daemon off;'
