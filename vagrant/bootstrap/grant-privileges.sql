use mysql;
grant all on *.* to 'root'@'%' with grant option;
update user set user='admin' where user='root';
flush privileges;
