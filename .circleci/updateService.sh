#!/bin/bash

export CLUSTER=FargateStack-fargateserviceautoscalingD107CF93-1L43D3RJL9411
export SERVICE=FargateStack-nameserviceServiceE5769334-J159BD0XSLXY
export TARGET="jobHunter-Family:${REVISION}"

echo $TARGET

aws ecs update-service \
    --cluster ${CLUSTER} \
    --service ${SERVICE} \
    --task-definition ${TARGET}