ALTER TABLE certificate_issuers MODIFY COLUMN id INT auto_increment;
ALTER TABLE certificate_issuers CHANGE COLUMN issuer_logo_url issuer_logo_file_id INT;
