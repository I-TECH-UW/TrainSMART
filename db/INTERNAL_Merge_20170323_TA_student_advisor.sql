/**********************************************************************
2017-03-23
Tamara Astakhova
student.advisorid=tutor.id where student.advisorid = tutor.personid
#337
**********************************************************************/
RUN onlu itechweb_cham

/*create csv old
select id, advisorid from student order by id;
*/

UPDATE student, tutor SET student.advisorid = tutor.id WHERE student.advisorid = tutor.personid and student.advisorid>88;

/*create csv new
select id, advisorid from student order by id;
*/

Compare two csv files