#!/bin/sh

function usage {
    echo "Usage: $0 <new-database> <new-user>"
    exit 1
}

[ -z "$1" ] && usage;
[ -z "$2" ] && usage;

mysql -u root -p mysql <<EOF
create database $1
create user '$2'@'localhost' identified by 'password';
grant all privileges on *.* to '$2'@'localhost' with grant option;
EOF
