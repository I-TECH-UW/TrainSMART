/* ALTER IGNORE makes key_phrase unique and drops any entries after the first
that have a duplicate key_phrase */

ALTER IGNORE TABLE translation ADD UNIQUE (key_phrase);