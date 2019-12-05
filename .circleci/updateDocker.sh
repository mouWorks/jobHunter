#!/bin/bash

set -o allexport
source .env
set +o allexport

export IMAGE_WITH_TAGS="mouwang/jobhunter-nginx-php:0.1.${BUILD_NUM}"

echo ">>> Remove Existing Folder"
#if [ -d /tmp/jobHunter_deploy ]; then
#    rm -rf /tmp/jobHunter_deploy
#fi
if [ -d /tmp/jobHunter_backup ]; then
    rm -rf /tmp/jobHunter_backup
fi

cd /tmp/jobHunter_deploy && \
sudo docker pull ${IMAGE_WITH_TAGS} && \  # Pull Newest Image
sudo docker stop jobHunter && \           # Stop Current Container
sudo docker rm jobHunter && \             # Remove Current Container
sudo docker run -d --name jobHunter -p 80:80 --env-file .env  ${IMAGE_WITH_TAGS} && \ # Run Container w/Newest Image
sudo docker image prune -a -f # Remove previous Images (To save space)

echo  ${IMAGE_WITH_TAGS}

echo ">>> Move This Deploy to backup"
if [ -d /tmp/jobHunter_deploy ]; then
    sudo mv /tmp/jobHunter_deploy /tmp/jobHunter_backup
fi
