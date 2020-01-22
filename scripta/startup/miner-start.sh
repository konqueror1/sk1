#!/bin/bash
cgminer=`ps -ef |grep gominer |grep -v grep`
if [ "$cgminer" == "" ]
        then
        echo `date +"%Y-%m-%d %T"`" gominer is not running, starting now..."
/usr/bin/screen -dmS gominer /opt/scripta/bin/gominer
fi
