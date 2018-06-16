#!/usr/bin/env bash

if [ $# !=  5 ]; then
  echo "ERROR: expected 5 arguments" 1>&2
  exit 1
fi

email=$(awk -F "=" '/^email/ {print $2}' ../../config-user.ini | sed -e 's/^ *//g')
password=$(awk -F "=" '/^password/ {print $2}' ../../config-user.ini | sed -e 's/^ *//g')
displayName=$(awk -F "=" '/^display_name/ {print $2}' ../../config-user.ini | sed -e 's/^ *//g')
phone=$(awk -F "=" '/^phone_number/ {print $2}' ../../config-user.ini | sed -e 's/^ *//g')

hash=`bcrypt-hash $password`

mysql $1 -h $4 --port=$5 -u $2 -p$3 -e "insert into users (email, displayName, phone, password, isActive, groupID) values ('$email', '$displayName', '$phone', '$hash', 1, (select id from groups where name = 'Root'))" 2>/dev/null
