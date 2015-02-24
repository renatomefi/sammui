#!/bin/bash
#Want to execute SF2 commands inside your vagrant?
#This way you can use i.e.: $`sammui router:debug`

sammui_host_folder='~/Vagrant/sammui'; #Sammui folder on your pc: Usually inside Vagrant folder
sammui_guest_folder='/vagrant/sammui'; #Sammui folder inside vagrant
sammui_vagrant_ip='www.sammui.dev'; #Your vagrant IP
sammui_vagrant_port='22'; #If you don't have an IP you can use the vagrant default redirect

alias sammui="
    ssh -p $sammui_vagrant_port -i $sammui_host_folder/docs/vagrant/id_rsa
    vagrant@$sammui_vagrant_ip
    $sammui_guest_folder/app/console"