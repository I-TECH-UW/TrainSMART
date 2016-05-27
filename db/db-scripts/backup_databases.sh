DATE=`date +%Y%m%d`
LONGDATE=`date +%Y%m%d-%H%M%S`

backups=backup/databases/$DATE/
mkdir -p $backups

for dbname in $( cat databases.txt )
do
  backupFile=$backups
  backupFile+=$dbname
  backupFile+=_$LONGDATE.sql
  echo "mysqldump --add-drop-database --create-options --databases $dbname > $backupFile"
  mysqldump --add-drop-database --create-options --databases $dbname | \
  sed -e 's/\/\*!50017 DEFINER=`.*`@`.*`\*\///' > $backupFile
done


