#!/bin/bash

sammui_project_folder='/home/sammui/project'; #Sammui folder on your pc: Usually inside Vagrant folder
sammui_dist_folder='/home/sammui/dist'; #Sammui folder inside vagrant

echo "-------- Removing Cache"
sudo rm -rf $sammui_dist_folder/app/cache/*
sudo rm -rf $sammui_dist_folder/app/logs/*
echo "-------- Syncing Files"
rsync -avh --delete --exclude app/logs --exclude app/cache --exclude .git --exclude .idea $sammui_project_folder $sammui_dist_folder
echo "-------- Starting PHPUnit"
php $sammui_dist_folder/bin/phpunit -c $sammui_dist_folder/app --coverage-html $sammui_project_folder/app/logs/phpunit/$(date +%s)/