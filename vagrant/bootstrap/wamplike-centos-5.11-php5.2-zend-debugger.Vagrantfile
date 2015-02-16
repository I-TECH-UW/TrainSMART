# -*- mode: ruby -*-
# vi: set ft=ruby :

# CentOS x86_64 5.11 PHP 5.2.17 with Zend Debugger - does not work on OSX or Linux

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  # All Vagrant configuration is done here. The most common configuration
  # options are documented and commented below. For a complete reference,
  # please see the online documentation at vagrantup.com.

  # Every Vagrant virtual environment requires a box to build off of.
  config.vm.box = "chef/centos-5.11"
  config.vm.hostname = "php52-zend-wamplike"
  
  # forward http - this part only works on windows or maybe as root. Don't run this as root.
  config.vm.network "forwarded_port", host: 80, guest: 80
  
  # forward mysql
  config.vm.network "forwarded_port", host: 3306, guest: 3306  

  # we'll put all the custom files in the vagrant user's home directory as we 
  # can't write to system locations without root, we'll move them in 
  # bootstrap-xx.sh when we're root

  config.vm.provision "file", source: "vagrant-bootstrap-files/vagrant-trainsmart-httpd.conf", destination: "/home/vagrant/vagrant-trainsmart-httpd.conf"

  config.vm.provision "file", source: "vagrant-bootstrap-files/data.sql", destination: "/home/vagrant/data.sql"
  config.vm.provision "file", source: "vagrant-bootstrap-files/grant-privileges.sql", destination: "/home/vagrant/grant-privileges.sql"

  config.vm.provision "file", source: "vagrant-bootstrap-files/zend-debugger.ini", destination: "/home/vagrant/php-debugger.ini"
  config.vm.provision "file", source: "vagrant-bootstrap-files/ZendDebugger-php5.2.so", destination: "/home/vagrant/ZendDebugger.so"
  config.vm.provision "file", source: "vagrant-bootstrap-files/php-5.2.ini", destination: "/home/vagrant/php.ini"
  config.vm.provision "file", source: "vagrant-bootstrap-files/rc.local.append", destination: "/home/vagrant/rc.local.append"
  config.vm.provision "file", source: "vagrant-bootstrap-files/mysqld.init.d", destination: "/home/vagrant/mysqld.init"

  config.vm.provision :shell, path: "vagrant-bootstrap-files/bootstrap-php5.2-zend-debugger.sh"
end
