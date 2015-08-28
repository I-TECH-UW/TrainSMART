# -*- mode: ruby -*-
# vi: set ft=ruby :

# CentOS x86_64 6.7 PHP 5.6.xx with Zend Debugger
# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  # All Vagrant configuration is done here. The most common configuration
  # options are documented and commented below. For a complete reference,
  # please see the online documentation at vagrantup.com.

  # Every Vagrant virtual environment requires a box to build off of.
  config.vm.box = "box-cutter/centos67"
  config.vm.hostname = "centos6-php56-zend"
  
  # forward http
  config.vm.network "forwarded_port", host: 62326, guest: 80
  
  # forward https
  config.vm.network "forwarded_port", host: 62443, guest: 443
  
  # forward mysql
  config.vm.network "forwarded_port", host: 3306, guest: 3306
  
  
  
  # we'll put all the custom files in the vagrant user's home directory as we 
  # can't write to system locations without root, we'll move them in 
  # bootstrap-xx.sh when we're root

  config.vm.provision "file", source: "vagrant/bootstrap/vagrant-trainsmart-httpd.conf", destination: "/home/vagrant/vagrant-trainsmart-httpd.conf"
  config.vm.provision "file", source: "vagrant/bootstrap/ssl.conf", destination: "/home/vagrant/ssl.conf"
  config.vm.provision "file", source: "vagrant/bootstrap/selinux-config", destination: "/home/vagrant/selinux-config"

  config.vm.provision "file", source: "vagrant/bootstrap/data.sql", destination: "/home/vagrant/data.sql"
  config.vm.provision "file", source: "vagrant/bootstrap/grant-privileges.sql", destination: "/home/vagrant/grant-privileges.sql"

  config.vm.provision "file", source: "vagrant/bootstrap/zend-debugger.ini", destination: "/home/vagrant/php-debugger.ini"
  config.vm.provision "file", source: "vagrant/bootstrap/ZendDebugger-php5.6.so", destination: "/home/vagrant/ZendDebugger.so"
  config.vm.provision "file", source: "vagrant/bootstrap/php-5.3-nobuffer.ini", destination: "/home/vagrant/php.ini"
#  config.vm.provision "file", source: "vagrant/bootstrap/rc.local.append", destination: "/home/vagrant/rc.local.append"
#  config.vm.provision "file", source: "vagrant/bootstrap/mysqld.init.d", destination: "/home/vagrant/mysqld.init"
  
  config.vm.provision "file", source: "vagrant/bootstrap/my-56.cnf", destination: "/home/vagrant/my.cnf"
  config.vm.provision "file", source: "vagrant/bootstrap/ius-archive.repo", destination: "/home/vagrant/ius-archive.repo"
  config.vm.provision :shell, path: "vagrant/bootstrap/bootstrap-centos6-php56-zend-debugger.sh"
  
  # these two commands restart the httpd and mysql processes after the vagrant folder is mounted
  # so log files can be written in the shared vagrant folder
  config.vm.provision :shell, :inline => "sudo service httpd restart", run: "always"
  config.vm.provision :shell, :inline => "sudo service mysqld restart", run: "always"
end
