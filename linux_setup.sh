#!/bin/bash
#SETUP ENVIRONMENT
#sudo su

#update-alternatives --config editor #(choose vim-tiny)

apt-get -y upgrade
apt-get -y install build-essential binutils-doc curl screen git vim python-setuptools 
apt-get -y install apache2
apt-get -y install php5 php5-curl php5-mysql php5-sqlite php5-xdebug php5-mcrypt php-pear php5-gd php5-cli
apt-get -y install nginx-full uwsgi uwsgi-plugin-python
apt-get -y install libapache2-mod-wsgi libapache2-mod-php5
apt-get -y build-dep python
apt-get -y install python-dev python-virtualenv
apt-get -y install libxml2-dev libpq-dev
apt-get -y install memcached

add-apt-repository ppa:webupd8team/sublime-text-2
apt-get update
apt-get install sublime-text


# Install MySQL without password prompt
# Set username and password to 'root'
debconf-set-selections <<< "mysql-server mysql-server/root_password password root"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password root"

# Install MySQL Server
# -qq implies -y --force-yes
apt-get install -qq mysql-server-5.5
apt-get install -qq python-mysqldb libmysqlclient-dev

# adding grant privileges to mysql root user from everywhere
MYSQL=`which mysql`

Q1="GRANT ALL ON *.* TO 'root'@'%' IDENTIFIED BY 'root' WITH GRANT OPTION;"
Q2="FLUSH PRIVILEGES;"                                                                                                                                                                                                                                                        
SQL="${Q1}${Q2}"

$MYSQL -uroot -proot -e "$SQL"

apt-get -y autoremove

