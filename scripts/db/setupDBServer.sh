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

./runSchema.sh $dbname $username $password $ip $port
./initData.sh $dbname $username $password $ip $port
./createUser.sh $dbname $username $password $ip $port

if [ ! -z "$1" ]; then
  ./runSchema.sh "test" $username $password $ip $port
fi
