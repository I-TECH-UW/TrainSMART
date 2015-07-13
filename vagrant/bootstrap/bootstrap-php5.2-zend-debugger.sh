# add package index for git
rpm -Uvh http://dl.fedoraproject.org/pub/epel/5/i386/epel-release-5-4.noarch.rpm

# add package index for php 5.2
rpm -Uvh http://mirror.webtatic.com/yum/centos/5/latest.rpm

# update base packages
yum update -y

# install our custom packages
yum install nano mysql-server git-core dos2unix telnet -y

# webtatic seems to have pulled php 5.2, use a different source of unsigned packages
wget http://vuksan.com/centos/RPMS/x86_64/php-common-5.2.17-1.x86_64.rpm
wget http://vuksan.com/centos/RPMS/x86_64/php-cli-5.2.17-1.x86_64.rpm
wget http://vuksan.com/centos/RPMS/x86_64/php-5.2.17-1.x86_64.rpm
wget http://vuksan.com/centos/RPMS/x86_64/php-mysql-5.2.17-1.x86_64.rpm
wget http://vuksan.com/centos/RPMS/x86_64/php-pdo-5.2.17-1.x86_64.rpm
wget http://vuksan.com/centos/RPMS/x86_64/php-devel-5.2.17-1.x86_64.rpm
wget http://vuksan.com/centos/RPMS/x86_64/php-xml-5.2.17-1.x86_64.rpm
wget http://vuksan.com/centos/RPMS/x86_64/libcurl-7.18.2-7.x86_64.rpm

yum --nogpgcheck --enablerepo=webtatic install --skip-broken libcurl*.rpm -y
yum --nogpgcheck --enablerepo=webtatic install --skip-broken *.rpm -y

rm *.rpm

# install the zend debugger for php 5.2 from http://www.zend.com/en/download/534?start=true
mv /home/vagrant/ZendDebugger.so /usr/lib64/php/modules/ZendDebugger.so
ln -s /lib64/libssl.so.0.9.8e /lib64/libssl.so.0.9.8
ln -s /lib64/libcrypto.so.0.9.8e /lib64/libcrypto.so.0.9.8
/sbin/ldconfig

# configure the php debugger
dos2unix /home/vagrant/php-debugger.ini
mv /home/vagrant/php-debugger.ini /etc/php.d/php-debugger.ini

# Change php's default output buffering to Off for better IDE support
dos2unix /home/vagrant/php.ini
mv /home/vagrant/php.ini /etc/
chmod 644 /etc/php.ini

# add the hack in to restart mysql and apache (hopefully) after shared folders are mounted
dos2unix /home/vagrant/rc.local.append
cat /home/vagrant/rc.local.append >> /etc/rc.local
rm /home/vagrant/rc.local.append

# set up apache to point to shared /vagrant folder and start it
dos2unix /home/vagrant/vagrant-trainsmart-httpd.conf
mv /home/vagrant/vagrant-trainsmart-httpd.conf /etc/httpd/conf.d/vagrant-trainsmart-httpd.conf
chkconfig httpd on
service httpd start

#enable query logging in mysql
echo "log=/vagrant/vagrant/logs/mysql-query.log" >> /etc/my.cnf
echo "" >> /etc/my.cnf
dos2unix /home/vagrant/mysqld.init
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


