/**********************************************************************
2017-03-31
Tamara Astakhova
Student advisorid=44 was wrong assigned to students, she must be only for students from institution=6
#337
**********************************************************************/
RUN only itechweb_cham

update student set advisorid='' where advisorid=44 and institutionid !=6