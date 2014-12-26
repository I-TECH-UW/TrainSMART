CREATE TABLE `link_employee_mechanism` (
  `id` int(11) NOT NULL auto_increment,
  `employee_id` int(11) NOT NULL DEFAULT 0,
  `mechanism_option_id` int(11) NOT NULL DEFAULT 0,
  `percentage` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);
  
CREATE TABLE `link_subpartner_mechanism` (
  `id` int(11) NOT NULL auto_increment,
  `partner_id` int(11) NOT NULL DEFAULT 0,
  `mechanism_option_id` int(11) NOT NULL DEFAULT 0,
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
);

INSERT INTO link_employee_mechanism (employee_id, mechanism_option_id, percentage) SELECT employee_id, mechanism_option_id, percentage FROM employee_to_partner_to_subpartner_to_funder_to_mechanism;

INSERT INTO `link_subpartner_mechanism` (partner_id, mechanism_option_id, end_date) SELECT subpartner_id, mechanism_option_id, funding_end_date FROM subpartner_to_funder_to_mechanism INNER JOIN mechanism_option ON subpartner_to_funder_to_mechanism.mechanism_option_id = mechanism_option.id WHERE mechanism_option.owner_id != subpartner_to_funder_to_mechanism.subpartner_id;

