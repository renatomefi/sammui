#!/bin/bash

rsync -avzhe --delete --progress --exclude app/logs --exclude app/cache --exclude .git --exclude .idea /home/sammui/project/ /home/sammui/dist/