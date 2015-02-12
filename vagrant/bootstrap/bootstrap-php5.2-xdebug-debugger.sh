# add package index for git
rpm -Uvh http://dl.fedoraproject.org/pub/epel/5/i386/epel-release-5-4.noarch.rpm

# add package index for php 5.2
rpm -Uvh http://mirror.webtatic.com/yum/centos/5/latest.rpm

# update base packages
yum update -y

# install our custom packages
yum install nano mysql-server git-core -y
yum --enablerepo=webtatic install php-common-5.2.17 php-cli-5.2.17 php-5.2.17 php-mysql-5.2.17 php-pdo-5.2.17 php-devel-5.2.17 -y

# install the xdebug debugger for php 5.2
mv /home/vagrant/xdebug.so /usr/lib64/php/modules/xdebug.so

# configure the php debugger
mv /home/vagrant/php-debugger.ini /etc/php.d/php-debugger.ini

# Change php's default output buffering to Off for better IDE support
mv /home/vagrant/php.ini /etc/
chmod 644 /etc/php.ini

# add the hack in to restart mysql and apache (hopefully) after shared folders are mounted
cat /home/vagrant/rc.local.append >> /etc/rc.local
rm /home/vagrant/rc.local.append

# set up apache to point to shared /vagrant folder and start it
mv /home/vagrant/vagrant-trainsmart-httpd.conf /etc/httpd/conf.d/vagrant-trainsmart-httpd.conf
chkconfig httpd on
service httpd start

#enable query logging in mysql
echo "log=/vagrant/vagrant/logs/mysql-query.log" >> /etc/my.cnf
echo "" >> /etc/my.cnf
mv /home/vagrant/mysqld.init /etc/init.d/mysqld
chmod 755 /etc/init.d/mysqld

# start up mysql, import data, grant remote access
chkconfig mysqld on
service mysqld start
mysql -u root </home/vagrant/grant-privileges.sql
mysql -u root </home/vagrant/data.sql
rm /home/vagrant/data.sql
service mysqld restart

# let's make it so we can look at the log files without being root
chmod -R a+rX /var/log


