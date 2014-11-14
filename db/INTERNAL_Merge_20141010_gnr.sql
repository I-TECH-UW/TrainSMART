alter table training modify column training_length_value int(11) default '0'; 
alter table training modify column training_length_interval enum('hour','week','day') default 'hour'; 

