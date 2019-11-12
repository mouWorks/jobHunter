#!/bin/bash

aws ecs update-service \
    --cluster FargateStack-fargateserviceautoscalingD107CF93-10UIXIP3G84N5 \
    --service FargateStack-nameserviceServiceE5769334-YH1WVQUWCKP9 \
    --task-definition "jobHunter-Family:"+REVISION