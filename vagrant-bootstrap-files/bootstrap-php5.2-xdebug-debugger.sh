# add package index for git
rpm -Uvh http://dl.fedoraproject.org/pub/epel/5/i386/epel-release-5-4.noarch.rpm

# add package index for php 5.2
rpm -Uvh http://mirror.webtatic.com/yum/centos/5/latest.rpm

# update base packages
yum update -y

# install our custom packages
yum install nano mysql-server git-core yum-versionlock -y
yum --enablerepo=webtatic install php-common-5.2.17 php-cli-5.2.17 php-5.2.17 php-mysql-5.2.17 php-pdo-5.2.17 php-devel-5.2.17 -y

# install the zend debugger for php 5.2 from http://www.zend.com/en/download/534?start=true
mv /home/vagrant/xdebug.so /usr/lib64/php/modules/xdebug.so

mv /home/vagrant/php-debugger.ini /etc/php.d/php-debugger.ini

# set up apache to point to shared /vagrant folder and start it
mv /home/vagrant/vagrant-trainsmart-httpd.conf /etc/httpd/conf.d/vagrant-trainsmart-httpd.conf
chkconfig httpd on
service httpd start

# start up mysql, import data, grant remote access
chkconfig mysqld on
service mysqld start
mysql -u root </home/vagrant/grant-privileges.sql
mysql -u root </home/vagrant/data.sql
rm /home/vagrant/data.sql
service mysqld restart

# let's make it so we can look at the log files without being root
chmod -R a+rX /var/log


