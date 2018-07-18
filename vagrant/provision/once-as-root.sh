#!/usr/bin/env bash

#== Import script args ==

timezone=$(echo "$1")
database=$(echo "$2")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

info "Allocate swap for MariaDB"
fallocate -l 2048M /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile
echo '/swapfile none swap defaults 0 0' >> /etc/fstab

info "Configure locales"
apt-get install -y language-pack-ru-base language-pack-uk-base
update-locale LC_ALL="ru_UA.UTF-8"
dpkg-reconfigure locales

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Prepare mysql-apt-config and root password for MySQL"
debconf-set-selections <<< "mysql-apt-config mysql-apt-config/enable-repo select mysql-5.7"
debconf-set-selections <<< "mysql-apt-config mysql-apt-config/select-server select mysql-5.7"
debconf-set-selections <<< "mysql-community-server mysql-community-server/root-pass password root"
debconf-set-selections <<< "mysql-community-server mysql-community-server/re-root-pass password root"
echo "Done!"

info "Install alternative repo"
wget https://repo.mysql.com/mysql-apt-config_0.8.9-1_all.deb
dpkg -i mysql-apt-config_0.8.9-1_all.deb
rm mysql-apt-config_0.8.9-1_all.deb
LC_ALL=ru_RU.UTF-8 add-apt-repository ppa:ondrej/php -y

info "Update OS software"
apt-get update && apt-get dist-upgrade -y
apt-get install -y software-properties-common apt-transport-https lsb-release ca-certificates
apt-get autoremove -y

info "Install additional software"
apt-get install -y git zip unzip npm
apt-get install -y bash-completion mysql-community-server nginx gettext snmp
apt-get install -y php7.1-fpm php7.1-cli php7.1-curl php7.1-intl php7.1-mysql php7.1-mbstring php7.1-xml php7.1-zip php7.1-snmp
npm install less -g
ln -s /usr/bin/nodejs /usr/bin/node
echo "Done!"

info "Install Xdebug"
mkdir /var/log/xdebug && chown vagrant:vagrant /var/log/xdebug
apt-get install -y php-xdebug
cat << EOF > /etc/php/7.1/mods-available/xdebug.ini
zend_extension = xdebug.so
xdebug.default_enable = 1
xdebug.idekey = "vagrant"
xdebug.remote_enable = 1
xdebug.remote_autostart = 1
xdebug.remote_port = 9000
xdebug.remote_handler = dbgp
xdebug.remote_host = 192.168.125.1
EOF
echo "Done!"

info "Configure PHP"
sed -i "s@.*date.timezone.*@date.timezone = ${timezone}@" "/etc/php/7.1/fpm/php.ini"
sed -i "s@.*date.timezone.*@date.timezone = ${timezone}@" "/etc/php/7.1/cli/php.ini"
sed -i "s@;cgi.fix_pathinfo=1@cgi.fix_pathinfo=0@" "/etc/php/7.1/fpm/php.ini"
sed -i "s@post_max_size = 8M@post_max_size = 100M@" "/etc/php/7.1/fpm/php.ini"
sed -i "s@upload_max_filesize = 2M@upload_max_filesize = 100M@" "/etc/php/7.1/fpm/php.ini"
sed -i "s@max_execution_time.*@max_execution_time = 1000@" "/etc/php/7.1/fpm/php.ini"
sed -i "s@max_input_time.*@max_input_time = 1000@" "/etc/php/7.1/fpm/php.ini"
echo "Done!"

info "Configure PHP-FPM"
sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
sed -i 's/listen =.*/listen = \/var\/run\/php\/php7.1-fpm.sock/' /etc/php/7.1/fpm/pool.d/www.conf
sed -i 's@;*request_terminate_timeout.*@request_terminate_timeout = 1000@' /etc/php/7.1/fpm/pool.d/www.conf
echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Configure MySQL"
sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf
mysql -uroot -proot <<< "CREATE USER 'root'@'%' IDENTIFIED BY 'root'"
mysql -uroot -proot <<< "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'"
mysql -uroot -proot <<< "FLUSH PRIVILEGES"
echo "Done!"

info "Initailize databases for MySQL"
mysql -uroot -proot <<< "CREATE DATABASE short-url-generator CHARACTER SET utf8 COLLATE utf8_unicode_ci;"
mysql -uroot -proot <<< "CREATE DATABASE short-url-generator_tests CHARACTER SET utf8 COLLATE utf8_unicode_ci;"
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# http://tecadmin.net/install-phantomjs-on-ubuntu/
info "Install PhantomJS"
wget https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-2.1.1-linux-x86_64.tar.bz2
tar xvjf phantomjs-2.1.1-linux-x86_64.tar.bz2 -C /usr/local/share/
rm phantomjs-2.1.1-linux-x86_64.tar.bz2
ln -sf /usr/local/share/phantomjs-2.1.1-linux-x86_64/bin/phantomjs /usr/local/bin
cat << EOF > /etc/systemd/system/phantomjs.service
[Unit]
Description=PhantomJS WebDriver
After=network.target

[Service]
User=vagrant
Group=vagrant
ExecStart=/usr/local/bin/phantomjs --webdriver=4444
SuccessExitStatus=143
Restart=always

[Install]
WantedBy=multi-user.target
EOF
systemctl enable phantomjs
systemctl start phantomjs

info "Install Redis"
curl -O http://download.redis.io/redis-stable.tar.gz
tar xzvf redis-stable.tar.gz
rm -f redis-stable.tar.gz
cd redis-stable
make
# make test
make install
mkdir -p /etc/redis
cp redis.conf /etc/redis/
sed -i 's@supervised .*@supervised systemd@g' /etc/redis/redis.conf
sed -i 's@dir .*@dir /var/lib/redis@' /etc/redis/redis.conf
sed -i 's@pidfile .*@pidfile /var/run/redis.pid@' /etc/redis/redis.conf

cat << EOF > /etc/systemd/system/redis.service
[Unit]
Description=Redis In-Memory Data Store
After=network.target

[Service]
User=redis
Group=redis
ExecStart=/usr/local/bin/redis-server /etc/redis/redis.conf
ExecStop=/usr/local/bin/redis-cli shutdown
Restart=always

[Install]
WantedBy=multi-user.target
EOF
adduser --system --group --no-create-home redis
mkdir -p /var/lib/redis
chown redis:redis /var/lib/redis
chmod 770 /var/lib/redis
sysctl vm.overcommit_memory=1
systemctl enable redis
systemctl start redis
systemctl status redis
