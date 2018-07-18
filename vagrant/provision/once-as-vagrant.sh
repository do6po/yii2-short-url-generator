#!/usr/bin/env bash

#== Import script args ==

github_token=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

info "Configure composer"
composer config --global github-oauth.github.com ${github_token}
echo "Done!"

info "Install project dependencies"
cd /app
composer --no-progress --prefer-dist install

info "Init project"
php init --env=Development --overwrite=y

#info "Apply migrations"
##php yii migrate --migrationPath='@yii/rbac/migrations' --interactive=0
#php yii migrate --migrationPath='@yii/log/migrations' --interactive=0
#php yii migrate --migrationPath='@yii/i18n/migrations' --interactive=0
#php yii migrate --interactive=0
#
#info "Apply tests migrations"
##php yii_test migrate --migrationPath='@yii/rbac/migrations' --interactive=0
#php yii_test migrate --migrationPath='@yii/log/migrations' --interactive=0
#php yii_test migrate --migrationPath='@yii/i18n/migrations' --interactive=0
#php yii_test migrate --interactive=0
#
#info "Apply fixtures"
#php yii fixture "*" --interactive=0
#
#info "Extract messages"
#php yii message/extract @common/config/messages.php

info "Create bash-alias 'app' for vagrant user"
echo 'alias app="cd /app"' | tee /home/vagrant/.bash_aliases

info "Create bash-alias 'codecept' for Codeception run"
echo 'alias codecept="/app/vendor/bin/codecept"' | tee -a /home/vagrant/.bash_aliases

info "Enabling colorized prompt for guest console"
sed -i "s/#force_color_prompt=yes/force_color_prompt=yes/" /home/vagrant/.bashrc
