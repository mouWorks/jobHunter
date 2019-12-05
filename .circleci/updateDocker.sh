#!/bin/bash

export IMAGE_WITH_TAGS="mouwang/jobhunter-nginx-php:0.1.${CIRCLE_BUILD_NUM}"

sudo su && \
docker rm jobHunter && \
docker run -d --name jobHunter -p 80:80 --env-file .env  ${IMAGE_WITH_TAGS}
