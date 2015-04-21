
yum install epel-release -y

# add package index for php 5.4
wget http://dl.iuscommunity.org/pub/ius/stable/CentOS/6/x86_64/ius-release-1.0-13.ius.centos6.noarch.rpm
rpm -Uvh ius-release-1.0-13.ius.centos6.noarch.rpm

# copy repository config that enables legacy php (5.4) installation
mv /home/vagrant/ius-archive.repo /etc/yum.repos.d/ius-archive.repo

# disable SELinux for vagrant installs - production servers should not use this
# vagrant shared folder mapping may be able to be locked down to compensate.
setenforce 0
mv /home/vagrant/selinux-config /etc/selinux/config

# update base packages
yum update -y
yum install yum-plugin-replace php php-devel php-mysql mysql-server mysql-devel git-core dos2unix telnet nano -y

yum replace mysql-libs --replace-with mysql56u-libs -y
yum replace php --replace-with php54 -y

# install the Zend Debugger for php 5.4
mv /home/vagrant/ZendDebugger.so /usr/lib64/php/modules/ZendDebugger.so

# this is a hack to make zend debugger work - it presents the newer (1.0) libcrypto and libssl as the old (0.9.8) # version. this will work fine if and only if the functions that zend debugger calls in libcrypto 
# and libssl 0.9.8 are compatible with the implementations in 1.0
ln -s /usr/lib64/libssl.so.10 /usr/lib64/libssl.so.0.9.8
ln -s /usr/lib64/libcrypto.so.10 /usr/lib64/libcrypto.so.0.9.8
/sbin/ldconfig

# configure the php debugger
dos2unix /home/vagrant/php-debugger.ini
mv /home/vagrant/php-debugger.ini /etc/php.d/php-debugger.ini

# Change php's default output buffering to Off for better IDE support
dos2unix /home/vagrant/php.ini
mv /home/vagrant/php.ini /etc/
chmod 644 /etc/php.ini


# set up apache to point to shared /vagrant folder and start it
dos2unix /home/vagrant/vagrant-trainsmart-httpd.conf
mv /home/vagrant/vagrant-trainsmart-httpd.conf /etc/httpd/conf.d/vagrant-trainsmart-httpd.conf
# this is a sample of how to enable selinux access to a file, and used used for all files
# in the event of production deployment of trainsmart
chcon -v --type=httpd_sys_content_t /etc/httpd/conf.d/vagrant-trainsmart-httpd.conf
chkconfig httpd on
service httpd start

#enable query logging in mysql
dos2unix /home/vagrant/my.cnf
mv /home/vagrant/my.cnf /etc/

# start up mysql, import data, grant remote access
chkconfig mysqld on
service mysqld start
mysql -u root </home/vagrant/grant-privileges.sql
mysql -u root </home/vagrant/data.sql
rm /home/vagrant/data.sql
service mysqld restart

# let's make it so we can look at the log files without being root
chmod -R a+rX /var/log


