#!/bin/bash

export TARGET="jobHunter-Family:${REVISION}"

echo $TARGET

aws ecs update-service \
    --cluster FargateStack-fargateserviceautoscalingD107CF93-1L43D3RJL9411 \
    --service FargateStack-nameserviceServiceE5769334-J159BD0XSLXY \
    --task-definition ${TARGET}