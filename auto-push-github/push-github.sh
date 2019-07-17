#!/bin/sh

day=`date +"%Y-%m-%d %H-%M-%S"`

cd /var/www/html/demo
git add *
git commit -m "$day autocommit"
git push