/**********************************************************************
2017-02-23
Tamara Astakhova
Employee edit add role to qualification filter
#329
**********************************************************************/

/* cross all sites*/
ALTER TABLE employee_qualification_option ADD COLUMN role_option_id int(11) NOT NULL;

 
/* only peprafskillsmart, hridtest, hridtest2*/
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%NC03-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%C3020100-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%C3020200-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%C3050600-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%C3020000-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%C3010000-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%C3010200-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%C3050100-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%D2020200-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%C3050300-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%C3050200-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%C3050500-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%F2010000-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%C4010000-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%F2020000-%';
update employee_qualification_option set role_option_id=1 where qualification_phrase like '%C4020000-%';
update employee_qualification_option set role_option_id=2 where qualification_phrase like '%D2020300-%';
update employee_qualification_option set role_option_id=2 where qualification_phrase like '%C3060100-%';
update employee_qualification_option set role_option_id=2 where qualification_phrase like '%NC01-%';
update employee_qualification_option set role_option_id=2 where qualification_phrase like '%C3030200-%';
update employee_qualification_option set role_option_id=2 where qualification_phrase like '%D2010400-%';
update employee_qualification_option set role_option_id=2 where qualification_phrase like '%C3030000-%';
update employee_qualification_option set role_option_id=2 where qualification_phrase like '%B2020400-%';
update employee_qualification_option set role_option_id=2 where qualification_phrase like '%C3030100-%';
update employee_qualification_option set role_option_id=2 where qualification_phrase like '%C2010200-%';
update employee_qualification_option set role_option_id=2 where qualification_phrase like '%C3050400-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%NC05-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%C6030200-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%A1050000-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%C5030100-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%C6020100-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%B1010200-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%C1020200-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%NC04-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%C6010100-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%C6010300-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%C6010200-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%J2000000-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%J1000000-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%J3000000-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%B1020200-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%A2010000-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%B1010100-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%B1010600-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%C6020200-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%B1010400-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%C6030100-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%H3010000-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%A1030000-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%A102000-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%C5080100-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%C5040200-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%C1030200-%';
update employee_qualification_option set role_option_id=3 where qualification_phrase like '%D2010300-%';
INSERT INTO employee_role_option (role_phrase) values ('Other');
update employee_qualification_option set role_option_id=6 where qualification_phrase like '%F1010000-%';
update employee_qualification_option set role_option_id=4 where qualification_phrase like '%C5040500-%';
update employee_qualification_option set role_option_id=4 where qualification_phrase like '%D2010600-%';
update employee_qualification_option set role_option_id=4 where qualification_phrase like '%C5040300-%';
update employee_qualification_option set role_option_id=4 where qualification_phrase like '%C5040400-%';
update employee_qualification_option set role_option_id=4 where qualification_phrase like '%F1030000-%';
update employee_qualification_option set role_option_id=4 where qualification_phrase like '%C5040600-%';
update employee_qualification_option set role_option_id=5 where qualification_phrase like '%NC02-%';
update employee_qualification_option set role_option_id=5 where qualification_phrase like '%E2010000-%';
update employee_qualification_option set role_option_id=5 where qualification_phrase like '%E2020000-%';
update employee_qualification_option set role_option_id=5 where qualification_phrase like '%Lay Counselor';


