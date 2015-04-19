#!/bin/bash

PWD=$1

mysql ROBLOX < create.sql -u root -p$PWD
