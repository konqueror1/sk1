#!/bin/bash
cgminer=`/bin/pidof gominer`
if [ "$cgminer" == "" ]
        then
        echo `date +"%Y-%m-%d %T"`" miner is not running, starting now..."
        /usr/bin/screen -dmS gominer /opt/scripta/bin/gominer
    else
        echo "Miner is already running!"
fi
