/**********************************************************************
2017-02-16
Tamara Astakhova
Add possibility to set person as a trainer from training page.
#345
**********************************************************************/

insert into trainer (person_id) (select distinct trainer_id from training_to_trainer where trainer_id not in (select person_id from trainer));

ALTER TABLE `training_to_trainer` 
ADD CONSTRAINT `training_to_trainer_ibfk_1`
  FOREIGN KEY (`trainer_id`)
  REFERENCES `trainer` (`person_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

