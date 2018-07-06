#!/usr/bin/env bash

if [ $# !=  5 ]; then
  echo "ERROR: expected 5 arguments" 1>&2
  exit 1
fi

mysql $1 -h $4 --port=$5 -u $2 -p$3 < phpauth_init_data.sql 2>/dev/null
mysql $1 -h $4 --port=$5 -u $2 -p$3 < phpauth_init_email_sms_base_data.sql 2>/dev/null
mysql $1 -h $4 --port=$5 -u $2 -p$3 < user_init_data.sql 2>/dev/null
mysql $1 -h $4 --port=$5 -u $2 -p$3 < moodboard_init_data.sql 2>/dev/null
mysql $1 -h $4 --port=$5 -u $2 -p$3 < styleguide_init_data.sql 2>/dev/null
mysql $1 -h $4 --port=$5 -u $2 -p$3 < admin_init_data.sql 2>/dev/null

smsEnabled=$(awk -F "=" '/^sms_enabled/ {print $2}' ../../config-sms.ini | sed -e 's/^ *//g')

mysql $1 -h $4 --port=$5 -u $2 -p$3 -e "INSERT INTO config (setting, value) VALUES ('sms', $smsEnabled)" 2>/dev/null
