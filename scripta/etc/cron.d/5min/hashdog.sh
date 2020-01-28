#! /bin/bash

RRDDIR="/opt/scripta/var/rrd/";
RRDTOOL="/usr/bin/rrdtool";
URL="http://localhost:1234/gominer/f_status";
JQ="/usr/bin/jq";
CURL="/usr/bin/curl -s";
BC="/usr/bin/bc";
HASHRATE1=`echo \`$CURL $URL | $JQ '.status.devs[0].hashrate[0]'\` / 1000 | $BC`;
HASHRATE5=`echo \`$CURL $URL | $JQ '.status.devs[0].hashrate[1]'\` / 1000 | $BC`;
ALGO=`$CURL $URL | $JQ -r '.status.devs[0].algo'`;
RRD="$RRDDIR$ALGO-hashrate.rrd";
UPTIME=`ps -o etimes= -p \`pgrep -fo gominer\` | tr -d [:space:]`;

#Check if miner is running. If not - start it
MINERPID=`/bin/pidof gominer`;
if [ "$MINERPID" == "" ]; then
    /opt/scripta/startup/miner-start.sh;
fi

#If miner runs for more than 5 minutes and hashrate is zero
if [[ $UPTIME -gt 300 ]]; then
    if [ "$HASHRATE1" = "0" -a "$HASHRATE5" = "0" ]; then
        echo `date +"%Y-%m-%d %T"`" hashrate is zero, restarting miner...";
            /opt/scripta/startup/miner-stop.sh;
        sleep 5;
            /opt/scripta/startup/miner-start.sh;
    fi
fi

#If minutes multiple to 5 create/update rrd
if ! (( `date +%M` % 5 )); then
    if [ ! -e  $RRD ]; then
        echo Creating $RRD
        $RRDTOOL create $RRD \
        --step 300 \
        DS:hashrate:GAUGE:600:0:U \
        RRA:AVERAGE:0.5:1:288 \
        RRA:AVERAGE:0.5:12:168 \
        RRA:AVERAGE:0.5:228:365
    fi

    $RRDTOOL update $RRD `date +%s`:$HASHRATE1
fi
