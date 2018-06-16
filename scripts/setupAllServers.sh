#!/usr/bin/env bash

cd webServer
./setupWebServer.sh
cd ..

cd db
./setupDBServer.sh $1
cd ..
