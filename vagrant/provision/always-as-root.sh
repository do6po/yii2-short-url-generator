#!/usr/bin/env bash

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

sysctl vm.overcommit_memory=1

info "Restart web-stack"
systemctl restart php7.1-fpm
systemctl restart nginx
systemctl restart mysql
systemctl restart redis
