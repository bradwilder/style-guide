#!/usr/bin/env bash

cd ..
./setupAllServers.sh $1
cd viewer

host=$(awk -F "=" '/^host/ {print $2}' ../../config.ini | sed -e 's/^ *//g')
username=$(awk -F "=" '/^username/ {print $2}' ../../config.ini | sed -e 's/^ *//g')
password=$(awk -F "=" '/^password/ {print $2}' ../../config.ini | sed -e 's/^ *//g')
dbname=$(awk -F "=" '/^dbname/ {print $2}' ../../config.ini | sed -e 's/^ *//g')

IFS=':'; DBADDR_PARTS=($host); unset IFS;
ip=${DBADDR_PARTS[0]}
if [ ${#DBADDR_PARTS[@]} ==  2 ]; then
  port=${DBADDR_PARTS[1]}
fi

mysql $dbname -h $ip --port=$port -u $username -p$password < moodboard.sql 2>/dev/null
mysql $dbname -h $ip --port=$port -u $username -p$password < styleguide.sql 2>/dev/null

cp -R ./uploads/app/ ../../app/uploads/
cp -R ./uploads/img/ ../../app/assets/img/uploads/
