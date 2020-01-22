<?php
session_start();
if ( "127.0.0.1"!=$_SERVER['REMOTE_ADDR'] && (!isset($_SESSION['_logged_']) || $_SESSION['_logged_'] === false) ) {
        echo json_encode(false);
        die();
}

require('inc/miner.inc.php');
include('inc/settings.inc.php');

$algo = file_get_contents('/opt/scripta/var/rrd/algo');

$ret =  create_graph('mhsav-hour.png', '-1h', 'Last Hour', $algo)
  && create_graph('mhsav-day.png', '-1d', 'Last Day', $algo)
  && create_graph('mhsav-week.png', '-1w', 'Last Week', $algo)
  && create_graph('mhsav-month.png', '-1m', 'Last Month', $algo)
  && create_graph('mhsav-year.png', '-1y', 'Last Year', $algo);

echo $ret;    
 
function create_graph($output, $start, $title, $alg){
  $rrd = '/opt/scripta/var/rrd/';
  $png = '/opt/scripta/http/rrd/';

  $options = array(
    '--slope-mode',
    '--start', $start,
    '--title='.$title,
    '--vertical-label=Hash per second',
    '--lower-limit=0',
    '--color=BACK#fff',      
    '--color=CANVAS#fff',    
    '--color=SHADEB#fff',   
    '--color=SHADEA#fff',   
    'DEF:hashrate='.$rrd.$alg.'-hashrate.rrd:hashrate:AVERAGE',
    'CDEF:realspeed=hashrate,1000,*',
    'AREA:realspeed#ddd',
    'LINE:realspeed#000'
    );

  return rrd_graph($png.$output, $options);
}

?>
