
CREATE TABLE `link_mechanism_employee` (
  `id` int(11) NOT NULL auto_increment,
  `mechanism_option_id` int(11) NOT NULL DEFAULT 0,
  `employee_id` int(11) NOT NULL DEFAULT 0,
  `percentage` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);
  
CREATE TABLE `link_mechanism_subpartner` (
  `id` int(11) NOT NULL auto_increment,
  `mechanism_option_id` int(11) NOT NULL DEFAULT 0,
  `partner_id` int(11) NOT NULL DEFAULT 0,
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
);

INSERT INTO link_mechanism_employee (mechanism_option_id, employee_id, percentage) SELECT mechanism_option_id, employee_id, percentage FROM employee_to_partner_to_subpartner_to_funder_to_mechanism;

INSERT INTO link_mechanism_subpartner (mechanism_option_id, partner_id, end_date) SELECT mechanism_option_id, subpartner_id, funding_end_date FROM subpartner_to_funder_to_mechanism INNER JOIN mechanism_option ON subpartner_to_funder_to_mechanism.mechanism_option_id = mechanism_option.id WHERE mechanism_option.owner_id != subpartner_to_funder_to_mechanism.subpartner_id;

