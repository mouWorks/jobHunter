#!/usr/bin/env bash
# This file is used locally, integrated into CI already
aws ecs register-task-definition --cli-input-json file://taskDef.json