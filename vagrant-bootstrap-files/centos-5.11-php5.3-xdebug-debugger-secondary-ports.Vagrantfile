# -*- mode: ruby -*-
# vi: set ft=ruby :

# CentOS x86_64 5.11 PHP 5.3 with XDebug Debugger on ports that don't conflict with other vagrant vms

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  # All Vagrant configuration is done here. The most common configuration
  # options are documented and commented below. For a complete reference,
  # please see the online documentation at vagrantup.com.

  # Every Vagrant virtual environment requires a box to build off of.
  config.vm.box = "chef/centos-5.11"
  config.vm.hostname = "php53-xdebug-alt"
  
  # forward ssh
  config.vm.network "forwarded_port", host: 2223, guest: 22, id: "ssh"
  
  # forward http
  config.vm.network "forwarded_port", host: 8889, guest: 80
  
  # forward mysql
  config.vm.network "forwarded_port", host: 3307, guest: 3306  
    
  # we'll put all the custom files in the vagrant user's home directory as we 
  # can't write to system locations without root, we'll move them in 
  # bootstrap.sh when we're root

  config.vm.provision "file", source: "vagrant-bootstrap-files/vagrant-trainsmart-httpd.conf", destination: "/home/vagrant/vagrant-trainsmart-httpd.conf"

  config.vm.provision "file", source: "vagrant-bootstrap-files/data.sql", destination: "/home/vagrant/data.sql"
  config.vm.provision "file", source: "vagrant-bootstrap-files/grant-privileges.sql", destination: "/home/vagrant/grant-privileges.sql"

  config.vm.provision "file", source: "vagrant-bootstrap-files/xdebug-debugger.ini", destination: "/home/vagrant/php-debugger.ini"
  config.vm.provision "file", source: "vagrant-bootstrap-files/xdebug-php5.3.so", destination: "/home/vagrant/xdebug.so"

  config.vm.provision :shell, path: "vagrant-bootstrap-files/bootstrap-php5.3-xdebug-debugger.sh"
end
