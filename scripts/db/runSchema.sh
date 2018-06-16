#!/usr/bin/env bash

if [ $# !=  5 ]; then
  echo "ERROR: expected 5 arguments" 1>&2
  exit 1
fi

sed "s/%dbName%/$1/g" _createDB.sql > createDB.sql
mysql -h $4 --port=$5 -u $2 -p$3 < createDB.sql 2>/dev/null
rm -rf createDB.sql

mysql $1 -h $4 --port=$5 -u $2 -p$3 < phpauth_database_mysql.sql 2>/dev/null
mysql $1 -h $4 --port=$5 -u $2 -p$3 < user_schema.sql 2>/dev/null
mysql $1 -h $4 --port=$5 -u $2 -p$3 < moodboard_schema.sql 2>/dev/null
mysql $1 -h $4 --port=$5 -u $2 -p$3 < styleguide_schema.sql 2>/dev/null
mysql $1 -h $4 --port=$5 -u $2 -p$3 < admin_schema.sql 2>/dev/null
