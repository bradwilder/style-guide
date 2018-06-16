#!/usr/bin/env bash

./clean.sh

cd router
./router.sh
cd ..

mkdir ../../app/uploads
mkdir ../../app/uploads/style-guide
mkdir ../../app/assets/img/uploads
mkdir ../../app/assets/img/uploads/style-guide
mkdir ../../app/assets/img/uploads/moodboard
