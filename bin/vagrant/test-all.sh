#!/bin/bash

sammui_project_folder='/home/sammui/project/'; #Sammui folder on your pc: Usually inside Vagrant folder
sammui_dist_folder='/home/sammui/dist/'; #Sammui folder inside vagrant

echo "-------- Syncing Files"
rsync -avh --delete --exclude app/logs --exclude app/cache --exclude .git --exclude .idea $sammui_project_folder $sammui_dist_folder
echo "-------- Removing Cache"
rm -rf $sammui_dist_folder/app/cache/*
echo "-------- Starting PHPUnit"
php $sammui_dist_folder/bin/phpunit -c $sammui_dist_folder/app