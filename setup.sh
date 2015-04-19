#!/bin/bash

PWD=$1

mysql < create.sql -u root -p $PWD
