#!/bin/bash
sammui_vagrant_key='/home/renatomefi/WS/sammui/docs/vagrant/ssh/id_rsa'
sammui_project_path='/home/sammui/project'; #Sammui folder on your pc: Usually inside Vagrant folder
sammui_console_command='ssh -i $sammui_vagrant_key vagrant@www.sammui.dev php $sammui_project_path/app/console'

alias sammui='$sammui_console_command --env=dev'
alias sammui-test='$sammui_console_command --env=test'
alias sammui-prod='$sammui_console_command --env=prod'
alias sammui-ssh='ssh -i $sammui_vagrant_key vagrant@www.sammui.dev'