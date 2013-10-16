<?php
#
# Ubiquiti pnp4nagios template
#
# By Zig Fisher
# http://blog.flyrouter.net
#
# Colors helping table: http://html-color-codes.info/Cvetovye-kody-HTML/

$_C_WARNRULE  = '#FFFF00';
$_C_CRITRULE  = '#FF0000';
$_C_LINE      = '#000000';
$_C_SIGNAL    = '#04B4AE';
$_C_NOISE     = '#B40431';
$_C_CCQ       = '#DF7401';
$_C_RXRATE    = '#00FF40';
$_C_TXRATE    = '#2E64FE';
$_C_RXDATA    = '#00FF00';
$_C_TXDATA    = '#2E64FE';
$_C_AVERAGE   = '#FF0000';
$_C_USERS     = '#642EFE';
$_C_UPTIME    = '#DF7001';
$_C_AQUALITY  = '#ff00ff';
$_C_ACAPACITY = '#000099';

$_RXDATA    = $this->DS[0];
$_TXDATA    = $this->DS[1];
$_SIGNAL    = $this->DS[2];
$_NOISE     = $this->DS[3];
$_RXRATE    = $this->DS[4];
$_TXRATE    = $this->DS[5];
$_CCQ       = $this->DS[6];
$_WCON      = $this->DS[7];
$_LAVG      = $this->DS[8];
$_UPTIME    = $this->DS[9];

$_SIGMIN = min ($_SIGNAL['MIN'], $_NOISE['MIN']);
$_SIGMAX = max ($_SIGNAL['MAX'], $_NOISE['MAX']);

$ds_name[0] = "Network Interface Traffic";
$opt[0] = "--vertical-label 'traffic, bps' -b 1024 --title '{$this->MACRO['DISP_HOSTNAME']}' --lower-limit=0 ";
$def[0]  = "DEF:rxdata={$_RXDATA['RRDFILE']}:{$_RXDATA['DS']}:AVERAGE ";
$def[0] .= "DEF:txdata={$_TXDATA['RRDFILE']}:{$_TXDATA['DS']}:AVERAGE ";
$def[0] .= "CDEF:ibits=rxdata,8,* ";
$def[0] .= "CDEF:obits=txdata,8,* ";
$def[0] .= "AREA:ibits{$_C_RXDATA}:'in  ' ";
$def[0] .= "GPRINT:ibits:LAST:'%7.2lf %S last' ";
$def[0] .= "GPRINT:ibits:AVERAGE:'%7.2lf %S avg' ";
$def[0] .= "GPRINT:ibits:MAX:'%7.2lf %S max\\n' ";
$def[0] .= "AREA:obits{$_C_TXDATA}:'out ' " ;
$def[0] .= "GPRINT:obits:LAST:'%7.2lf %S last' " ;
$def[0] .= "GPRINT:obits:AVERAGE:'%7.2lf %S avg' " ;
$def[0] .= "GPRINT:obits:MAX:'%7.2lf %S max\\n' ";

$ds_name[1] = "Signal & Noise";
$opt[1] = "--vertical-label 'signal/noise, dBm' --title '{$this->MACRO['DISP_HOSTNAME']}' --alt-y-grid ";
$def[1]  = "DEF:signal={$_SIGNAL['RRDFILE']}:{$_SIGNAL['DS']}:AVERAGE ";
$def[1] .= "DEF:noise={$_NOISE['RRDFILE']}:{$_NOISE['DS']}:AVERAGE ";
$def[1] .= "CDEF:signalU=signal,0,EQ,NEGINF,signal,IF ";
$def[1] .= "CDEF:noiseU=noise,0,EQ,UNKN,noise,IF ";
$def[1] .= "CDEF:signalI=signalU,UN,UNKN,NEGINF,IF ";
$def[1] .= "CDEF:noiseI=noiseU,UN,UNKN,NEGINF,IF ";
$def[1] .= "LINE1:signalU{$_C_SIGNAL}:'signal' ";
$def[1] .= "AREA:signalI{$_C_SIGNAL}:'':STACK ";
$def[1] .= "GPRINT:signalU:LAST:'%7.2lf %S last' ";
$def[1] .= "GPRINT:signalU:AVERAGE:'%7.2lf %S avg' ";
$def[1] .= "GPRINT:signalU:MAX:'%7.2lf %S max' ";
$def[1] .= "GPRINT:signalU:MIN:'%7.2lf %S min'\\n ";
$def[1] .= "LINE1:noiseU{$_C_NOISE}:'noise ' ";
$def[1] .= "AREA:noiseI{$_C_NOISE}:'':STACK ";
$def[1] .= "GPRINT:noiseU:LAST:'%7.2lf %S last' ";
$def[1] .= "GPRINT:noiseU:AVERAGE:'%7.2lf %S avg' ";
$def[1] .= "GPRINT:noiseU:MAX:'%7.2lf %S max' ";
$def[1] .= "GPRINT:noiseU:MIN:'%7.2lf %S min'\\n ";
$def[1] .= "LINE1:signalU{$_C_LINE}:'' ";
$def[1] .= "LINE1:noiseU{$_C_LINE} ";

#if($this->MACRO['TIMET'] != ""){
#  $def[1] .= "VRULE:".$this->MACRO['TIMET']."#000000:\"Last Service Check \\n\" ";
#}
#if ($WARN[1] != "") {
#  $def[1] .= "HRULE:$WARN[1]{$_C_WARNRULE}:\"In-Traffic Warning on $WARN[1] \" ";
#}
#if ($CRIT[1] != "") {
#  $def[1] .= "HRULE:$CRIT[1]{$_C_CRITRULE}:\"In-Traffic Critical on $CRIT[1] \" ";
#}

$ds_name[2] = "Link quiality";
$opt[2] = "--vertical-label 'quality, %' --title '{$this->MACRO['DISP_HOSTNAME']}' --lower-limit=0 ";
$def[2] = "DEF:ccq={$_CCQ['RRDFILE']}:{$_CCQ['DS']}:AVERAGE ";
$def[2] .= "AREA:ccq{$_C_CCQ}:'ccq' ";
$def[2] .= "GPRINT:ccq:LAST:'%7.2lf %S last' ";
$def[2] .= "GPRINT:ccq:AVERAGE:'%7.2lf %S avg' ";
$def[2] .= "GPRINT:ccq:MAX:'%7.2lf %S max' ";
$def[2] .= "GPRINT:ccq:MIN:'%7.2lf %S min'\\n ";

$ds_name[3] = "Connect rate";
$opt[3] = "--vertical-label 'rate, Mbit/s' --title '{$this->MACRO['DISP_HOSTNAME']}' --lower-limit=0 ";
$def[3] = "DEF:rxrate={$_RXRATE['RRDFILE']}:{$_RXRATE['DS']}:AVERAGE ";
$def[3] .= "DEF:txrate={$_TXRATE['RRDFILE']}:{$_TXRATE['DS']}:AVERAGE ";
$def[3] .= "LINE1:rxrate{$_C_RXRATE}:'rx' ";
$def[3] .= "GPRINT:rxrate:LAST:'%7.2lf %S last' ";
$def[3] .= "GPRINT:rxrate:AVERAGE:'%7.2lf %S avg' ";
$def[3] .= "GPRINT:rxrate:MAX:'%7.2lf %S max' ";
$def[3] .= "GPRINT:rxrate:MIN:'%7.2lf %S min'\\n ";
$def[3] .= "LINE1:txrate{$_C_TXRATE}:'tx' ";
$def[3] .= "GPRINT:txrate:LAST:'%7.2lf %S last' ";
$def[3] .= "GPRINT:txrate:AVERAGE:'%7.2lf %S avg' ";
$def[3] .= "GPRINT:txrate:MAX:'%7.2lf %S max' ";
$def[3] .= "GPRINT:txrate:MIN:'%7.2lf %S min'\\n ";

$ds_name[4] = "Connected users";
$opt[4] = "--vertical-label 'people, ps' --title '{$this->MACRO['DISP_HOSTNAME']}' --lower-limit=0 ";
$def[4] = "DEF:users={$_WCON['RRDFILE']}:{$_WCON['DS']}:AVERAGE ";
$def[4] .= "AREA:users{$_C_USERS}:'users' ";
$def[4] .= "GPRINT:users:LAST:'%7.2lf %S last' ";
$def[4] .= "GPRINT:users:AVERAGE:'%7.2lf %S avg' ";
$def[4] .= "GPRINT:users:MAX:'%7.2lf %S max' ";
$def[4] .= "GPRINT:users:MAX:'%7.2lf %S min'\\n ";

$ds_name[5] = "Load average";
$opt[5] = "--vertical-label 'usage system' --title '{$this->MACRO['DISP_HOSTNAME']}' --lower-limit=0 ";
$def[5] = "DEF:lavg={$_LAVG['RRDFILE']}:{$_LAVG['DS']}:AVERAGE ";
$def[5] .= "AREA:lavg{$_C_AVERAGE}:'load' ";
$def[5] .= "GPRINT:lavg:LAST:'%7.2lf %S last' ";
$def[5] .= "GPRINT:lavg:AVERAGE:'%7.2lf %S avg' ";
$def[5] .= "GPRINT:lavg:MAX:'%7.2lf %S max' ";
$def[5] .= "GPRINT:lavg:MAX:'%7.2lf %S min'\\n ";

$ds_name[6] = "Uptime";
$opt[6] = "--vertical-label 'days' --title '{$this->MACRO['DISP_HOSTNAME']}' --slope-mode ";
#$def[6] = "DEF:uptime={$_UPTIME['RRDFILE']}:{$_UPTIME['DS']}:AVERAGE ";
#$def[6] .= "AREA:uptime{$_C_UPTIME}:'sec' ";
#$def[6] .= "GPRINT:uptime:LAST:'%7.2lf %S last' ";
#$def[6] .= "GPRINT:uptime:AVERAGE:'%7.2lf %S avg' ";
#$def[6] .= "GPRINT:uptime:MAX:'%7.2lf %S max' ";
#$def[6] .= "GPRINT:uptime:MAX:'%7.2lf %S min'\\n ";

$def[6]  =  DEF("minutes", $RRDFILE[2], $DS[2], "AVERAGE") ;
$def[6] .=  CDEF("days", "minutes,60,/,24,/");
#$def[6] .=  GRADIENT("days", "228b22", "adff2f", "uptime", 20) ;
$def[6] .=  GRADIENT("days", "00ff2f", "00ffff", "uptime", 20) ;
$def[6] .=  GPRINT("days", array("last", "avg", "max"), "%6.2lf days") ;
$def[6] .=  LINE1("days", "#000000")

?>
