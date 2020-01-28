#! /bin/bash

RRDDIR="/opt/scripta/var/rrd/";
RRDTOOL="/usr/bin/rrdtool";
JQ="/usr/bin/jq";
BC="/usr/bin/bc";

#1st check if uptime is greater than 60 seconds.
if [[ $(cut -d '.' -f 1 </proc/uptime) -lt 60 ]]; then
    echo Too early to start; exit 0;
fi

while [ "$(/bin/pidof openocd)" != "" ]; do
    sleep 2;
done

#Check if miner is running. If not - start it
MINERPID=`/bin/pidof gominer`;
if [ "$MINERPID" == "" ]; then
    /opt/scripta/startup/miner-start.sh;
    sleep 5;
fi

UPTIME=`ps -o etimes= -p \`pgrep -fo gominer\` | tr -d [:space:]`;

exec 818<>/dev/tcp/localhost/1234;
echo -e "GET /gominer/f_status HTTP/1.1\r\nhost: localhost\r\nConnection: close\r\n\r\n" >&818;
JSON=`grep "{" <&818`;

HASHRATE1=`echo \`echo $JSON | $JQ '.status.devs[0].hashrate[0]'\` / 1000 | $BC`;
HASHRATE5=`echo \`echo $JSON | $JQ '.status.devs[0].hashrate[1]'\` / 1000 | $BC`;
ALGO=`echo $JSON | $JQ -r '.status.devs[0].algo'`;
RRD="$RRDDIR$ALGO-hashrate.rrd";

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
