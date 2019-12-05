#!/bin/bash

export IMAGE_WITH_TAGS="mouwang/jobhunter-nginx-php:0.1.${CIRCLE_BUILD_NUM}"

echo ">>> Remove Existing Folder"
#if [ -d /tmp/jobHunter_deploy ]; then
#    rm -rf /tmp/jobHunter_deploy
#fi
if [ -d /tmp/jobHunter_backup ]; then
    rm -rf /tmp/jobHunter_backup
fi

#echo ">>> Create Temp Folder if Exists, And backup folder"
#mkdir /tmp/jobHunter_deploy
#
#echo ">>> Unzipping files into the folder"
#tar -xf jobHunter-code.tar.gz -C /tmp/jobHunter_deploy

cd /tmp/jobHunter_deploy && \
sudo docker rm jobHunter && \
sudo docker run -d --name jobHunter -p 80:80 --env-file .env  ${IMAGE_WITH_TAGS}

echo ">>> Move This Deploy to backup"
if [ -d /tmp/jobHunter_deploy ]; then
    sudo mv /tmp/jobHunter_deploy /tmp/jobHunter_backup
fi
