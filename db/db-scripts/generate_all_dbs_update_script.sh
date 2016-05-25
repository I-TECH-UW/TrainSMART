sourcefile=update.sql
batchfile=all_update.sql

if [ -f $batchfile ]
  then
    rm $batchfile
fi

for dbname in $( cat databases.txt )
do
  echo "use \`$dbname\`;" >> $batchfile
  echo "select DATABASE() as DB;" >> $batchfile
  cat $sourcefile >> $batchfile
done

