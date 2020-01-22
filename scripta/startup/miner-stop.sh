#!/bin/sh
echo `date +"%Y-%m-%d %T"`" stopping gominer"
sudo /usr/bin/screen -S gominer -X quit
