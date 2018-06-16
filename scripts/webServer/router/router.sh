#!/usr/bin/env bash

sqlite3 routes.db < schema.sql
sqlite3 routes.db < routes.sql
mv routes.db ../../../app/assets/php/Framework