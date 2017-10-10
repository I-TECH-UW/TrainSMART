/**********************************************************************
2017-10-10
Tamara Astakhova
Anticipated Updates access
#451
**********************************************************************/

insert into acl (id,acl) values ('anticipated_updates_access','anticipated_updates_access');
insert into user_to_acl (acl_id, user_id) select 'anticipated_updates_access', id from user;

