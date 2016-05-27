# this script was copied out of the roll_db.sh script

# Make a script from the list of database names that match the 
# convention for training sites ('itechweb_' concat subdomain name)

command="select schema_name"
command+=" from information_schema.schemata where schema_name like 
'itechweb_%'"
command+=" and schema_name not like '%drupal%'"

#DATE=`date +%Y%m%d-%H:%M:%S`
#DATE=`date +%Y%m%d`
# Generate a list of databases to operate on
mysql -B --skip-column-names -e "$command" > databases.txt

