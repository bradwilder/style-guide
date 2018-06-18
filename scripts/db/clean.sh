#!/usr/bin/env bash

host=$(awk -F "=" '/^host/ {print $2}' ../../config.ini | sed -e 's/^ *//g')
username=$(awk -F "=" '/^username/ {print $2}' ../../config.ini | sed -e 's/^ *//g')
password=$(awk -F "=" '/^password/ {print $2}' ../../config.ini | sed -e 's/^ *//g')
dbname=$(awk -F "=" '/^dbname/ {print $2}' ../../config.ini | sed -e 's/^ *//g')

IFS=':'; DBADDR_PARTS=($host); unset IFS;
ip=${DBADDR_PARTS[0]}
if [ ${#DBADDR_PARTS[@]} ==  2 ]; then
  port=${DBADDR_PARTS[1]}
fi

sed "s/%dbName%/$dbName/g" _dropDB.sql > dropDB.sql
mysql -h $ip --port=$port -u $username -p$password < dropDB.sql 2>/dev/null
rm -rf dropDB.sql

sed "s/%dbName%/test/g" _dropDB.sql > dropDB.sql
mysql -h $ip --port=$port -u $username -p$password < dropDB.sql 2>/dev/null
rm -rf dropDB.sql
