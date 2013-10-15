<?php
#
# Ubiquiti pnp4nagios template
#
# By Zig Fisher
# http://blog.flyrouter.net

# Colors table - http://html-color-codes.info/Cvetovye-kody-HTML/
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
$_C_AQUALITY  = '#ff00ff';
$_C_ACAPACITY = '#000099';

# Data sources
$_RXDATA    = $this->DS[0];
$_TXDATA    = $this->DS[1];
$_SIGNAL    = $this->DS[2];
$_NOISE     = $this->DS[3];
$_RXRATE    = $this->DS[4];
$_TXRATE    = $this->DS[5];
$_CCQ       = $this->DS[6];
$_WCON      = $this->DS[7];
$_LAVG      = $this->DS[8];
#$_AQUALITY  = $this->DS[9];
#$_ACAPACITY = $this->DS[10];

# Calculations
#$_SIGMIN = min ($_SIGNAL['MIN'], $_NOISE['MIN']);
#$_SIGMAX = max ($_SIGNAL['MAX'], $_NOISE['MAX']);


$ds_name[0] = "Network Interface Traffic";
$opt[0] = "--vertical-label 'traffic, bps' -b 1024 --title '{$this->MACRO['DISP_HOSTNAME']}' --lower-limit=0 ";
$def[0]  = "DEF:rxdata={$_RXDATA['RRDFILE']}:{$_RXDATA['DS']}:AVERAGE ";
$def[0] .= "DEF:txdata={$_TXDATA['RRDFILE']}:{$_TXDATA['DS']}:AVERAGE ";
$def[0] .= "CDEF:ibits=rxdata,8,* ";
$def[0] .= "CDEF:obits=txdata,8,* ";
$def[0] .= "AREA:ibits{$_C_RXDATA}:'in  ' ";
$def[0] .= "GPRINT:ibits:LAST:'%7.2lf %Sbit/s last' ";
$def[0] .= "GPRINT:ibits:AVERAGE:'%7.2lf %Sbit/s avg' ";
$def[0] .= "GPRINT:ibits:MAX:'%7.2lf %Sbit/s max\\n' ";
$def[0] .= "AREA:obits{$_C_TXDATA}:'out ' " ;
$def[0] .= "GPRINT:obits:LAST:'%7.2lf %Sbit/s last' " ;
$def[0] .= "GPRINT:obits:AVERAGE:'%7.2lf %Sbit/s avg' " ;
$def[0] .= "GPRINT:obits:MAX:'%7.2lf %Sbit/s max\\n' ";


$ds_name[1] = "Signal & Noise";
$opt[1] = "--vertical-label 'signal/noise, dBm' --title '{$this->MACRO['DISP_HOSTNAME']}' --alt-y-grid ";
$def[1]  = "DEF:signal={$_SIGNAL['RRDFILE']}:{$_SIGNAL['DS']}:AVERAGE ";
$def[1] .= "DEF:noise={$_NOISE['RRDFILE']}:{$_NOISE['DS']}:AVERAGE ";

## If noise or signal equal 0 then the link was down
$def[1] .= "CDEF:signalU=signal,0,EQ,NEGINF,signal,IF ";
$def[1] .= "CDEF:noiseU=noise,0,EQ,UNKN,noise,IF ";
#
## Drop values to -infinity for filling graph
$def[1] .= "CDEF:signalI=signalU,UN,UNKN,NEGINF,IF ";
$def[1] .= "CDEF:noiseI=noiseU,UN,UNKN,NEGINF,IF ";

# Plot values
$def[1] .= "LINE1:signalU{$_C_SIGNAL}:'Signal        ' ";
$def[1] .= "AREA:signalI{$_C_SIGNAL}:'':STACK ";
$def[1] .= "GPRINT:signalU:MIN:'%3.0lf dBm MIN ' ";
$def[1] .= "GPRINT:signalU:MAX:'%3.0lf dBm MAX ' ";
$def[1] .= "GPRINT:signalU:AVERAGE:'%3.0lf dBm AVG ' ";
$def[1] .= "GPRINT:signalU:LAST:'%3.0lf dBm LAST\\n' ";

$def[1] .= "LINE1:noiseU{$_C_NOISE}:'Noise         ' ";
$def[1] .= "AREA:noiseI{$_C_NOISE}:'':STACK ";
$def[1] .= "GPRINT:noiseU:MIN:'%3.0lf dBm min ' ";
$def[1] .= "GPRINT:noiseU:MAX:'%3.0lf dBm MAX ' ";
$def[1] .= "GPRINT:noiseU:AVERAGE:'%3.0lf dBm AVG ' ";
$def[1] .= "GPRINT:noiseU:LAST:'%3.0lf dBm LAST\\n' ";

$def[1] .= "LINE1:signalU{$_C_LINE}:'' ";
$def[1] .= "LINE1:noiseU{$_C_LINE} ";


# Define connection graph
#$ds_name[1] = "{$_CCQ['NAME']} {$_AQUALITY['NAME']} {$_ACAPACITY['NAME']}";
$ds_name[2] = "{$_CCQ['NAME']}";
$opt[2] = "--vertical-label 'Percent' --title '{$this->MACRO['DISP_HOSTNAME']} / {$this->MACRO['DISP_SERVICEDESC']} Quality' --lower-limit=0 ";

$def[2]  = "DEF:ccq={$_CCQ['RRDFILE']}:{$_CCQ['DS']}:AVERAGE ";
#$def[2] .= "DEF:aquality={$_AQUALITY['RRDFILE']}:{$_AQUALITY['DS']}:AVERAGE ";
#$def[2] .= "DEF:acapacity={$_ACAPACITY['RRDFILE']}:{$_ACAPACITY['DS']}:AVERAGE ";

#$def[2] .= "LINE1:ccq{$_C_CCQ}:'CCQ            ' ";
$def[2] .= "AREA:ccq{$_C_CCQ}:'CCQ            ' ";
$def[2] .= "GPRINT:ccq:MIN:'%3.0lf%% MIN ' ";
$def[2] .= "GPRINT:ccq:MAX:'%3.0lf%% MAX ' ";
$def[2] .= "GPRINT:ccq:AVERAGE:'%3.0lf%% AVG ' ";
$def[2] .= "GPRINT:ccq:LAST:'%3.0lf%% LAST\\n' ";

if($this->MACRO['TIMET'] != ""){
    $def[2] .= "VRULE:".$this->MACRO['TIMET']."#000000:\"Last Service Check \\n\" ";
}
if ($WARN[1] != "") {
    $def[2] .= "HRULE:$WARN[1]#FF8C00:\"In-Traffic Warning on $WARN[1] \" ";
}
if ($CRIT[1] != "") {
    $def[2] .= "HRULE:$CRIT[1]#FF008C:\"In-Traffic Critical on $CRIT[1] \" ";
}



$ds_name[3] = "Connect rate";
$opt[3] = "--vertical-label 'rate, Mbps' --title '{$this->MACRO['DISP_HOSTNAME']}' --lower-limit=0 ";
$def[3]  = "DEF:rxrate={$_RXRATE['RRDFILE']}:{$_RXRATE['DS']}:AVERAGE ";
$def[3] .= "DEF:txrate={$_TXRATE['RRDFILE']}:{$_TXRATE['DS']}:AVERAGE ";
$def[3] .= "LINE1:rxrate{$_C_RXRATE}:'rx' ";
$def[3] .= "GPRINT:rxrate:LAST:'%7.2lf %SM last' ";
$def[3] .= "GPRINT:rxrate:AVERAGE:'%7.2lf %SM avg' ";
$def[3] .= "GPRINT:rxrate:MAX:'%7.2lf %SM max' ";
$def[3] .= "GPRINT:rxrate:MIN:'%7.2lf %SM mim' ";
$def[3] .= "LINE1:txrate{$_C_TXRATE}:'tx' ";
$def[3] .= "GPRINT:txrate:LAST:'%7.2lf %SM last' ";
$def[3] .= "GPRINT:txrate:AVERAGE:'%7.2lf %SMbps avg' ";
$def[3] .= "GPRINT:txrate:MAX:'%7.2lf %SM max' ";
$def[3] .= "GPRINT:txrate:MIN:'%7.2lf %SM mim' ";



$ds_name[4] = "Connected users";
$opt[4] = "--vertical-label 'fucking people' --title '{$this->MACRO['DISP_HOSTNAME']}' --lower-limit=0 ";
$def[4] .= "DEF:lavg={$_LAVG['RRDFILE']}:{$_LAVG['DS']}:AVERAGE ";
$def[4] .= "AREA:lavg{$_C_USERS}:'* ' ";
$def[4] .= "GPRINT:lavg:LAST:'%7.2lf %Susers last' ";
$def[4] .= "GPRINT:lavg:AVERAGE:'%7.2lf %Susers avg' ";
$def[4] .= "GPRINT:lavg:MAX:'%7.2lf %Susers max' ";
$def[4] .= "GPRINT:lavg:MIN:'%7.2lf %Susers min' ";


$ds_name[5] = "Load average";
$opt[5] = "--vertical-label 'usage system' --title '{$this->MACRO['DISP_HOSTNAME']}' --lower-limit=0 ";
$def[5] .= "DEF:lavg={$_LAVG['RRDFILE']}:{$_LAVG['DS']}:AVERAGE ";
$def[5] .= "AREA:lavg{$_C_AVERAGE}:'* ' ";
$def[5] .= "GPRINT:lavg:LAST:'%7.2lf %Sload last' ";
$def[5] .= "GPRINT:lavg:AVERAGE:'%7.2lf %Sload avg' ";
$def[5] .= "GPRINT:lavg:MAX:'%7.2lf %Sload max' ";
$def[5] .= "GPRINT:lavg:MIN:'%7.2lf %Sload min' ";

?>
