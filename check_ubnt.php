<?php
# coding: utf-8
#
# Ubiquiti pnp4nagios template
#
# By Zig Fisher
# flyrouter@gmail.com
# http://blog.flyrouter.net
# Licence GPLv2
# Version 0.1


# Colors
$_C_WARNRULE  = '#FFFF00';
$_C_CRITRULE  = '#FF0000';
$_C_LINE      = '#000000';
$_C_SIGNAL    = '#256aef';
$_C_NOISE     = '#a00000';
$_C_CCQ       = '#000000';
$_C_AQUALITY  = '#ff00ff';
$_C_ACAPACITY = '#000099';
$_C_RXRATE    = '#ff00ff';
$_C_TXRATE    = '#000099';
$_C_RXDATA    = '#ff00ff';
$_C_TXDATA    = '#00ff00';

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
$_SIGMIN = min ($_SIGNAL['MIN'], $_NOISE['MIN']);
$_SIGMAX = max ($_SIGNAL['MAX'], $_NOISE['MAX']);


# Define data graph
$ds_name[0] = "{$_TXDATA['NAME']} {$_RXDATA['NAME']}";
$opt[0] = "--vertical-label 'Data Throughput' --title '{$this->MACRO['DISP_HOSTNAME']} / {$this->MACRO['DISP_SERVICEDESC']} Traffic' --lower-limit=0 ";

$def[0]  = "DEF:rxdata={$_RXDATA['RRDFILE']}:{$_RXDATA['DS']}:AVERAGE ";
$def[0] .= "DEF:txdata={$_TXDATA['RRDFILE']}:{$_TXDATA['DS']}:AVERAGE ";

$def[0] .= "CDEF:kbin='rxdata,1024' ";
$def[0] .= "CDEF:kbout='txdata,1024' ";

$def[0] .= "LINE1:kbin{$_C_RXDATA}:'Bandwidth In' ";
$def[0] .= "GPRINT:kbin:MIN:'%3.1lf KBps MIN ' ";
$def[0] .= "GPRINT:kbin:MAX:'%3.1lf KBps MAX ' ";
$def[0] .= "GPRINT:kbin:AVERAGE:'%3.1lf KBps AVG ' ";
$def[0] .= "GPRINT:kbin:LAST:'%3.1lf KBps LAST\\n' ";

$def[0] .= "LINE1:kbout{$_C_TXDATA}:'Bandwidth Out' ";
$def[0] .= "GPRINT:kbout:MIN:'%3.1lf KBps MIN ' ";
$def[0] .= "GPRINT:kbout:MAX:'%3.1lf KBps MAX ' ";
$def[0] .= "GPRINT:kbout:AVERAGE:'%3.1lf KBps AVG ' ";
$def[0] .= "GPRINT:kbout:LAST:'%3.1lf KBps LAST\\n' ";


# Define signal graph
$ds_name[1] = "{$_SIGNAL['NAME']} {$_NOISE['NAME']}";
$opt[1] = "--vertical-label 'dBm' --title '{$this->MACRO['DISP_HOSTNAME']} / {$this->MACRO['DISP_SERVICEDESC']} Signal' --alt-y-grid ";

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
$def[1] .= "GPRINT:noiseU:MIN:'%3.0lf dBm MIN ' ";
$def[1] .= "GPRINT:noiseU:MAX:'%3.0lf dBm MAX ' ";
$def[1] .= "GPRINT:noiseU:AVERAGE:'%3.0lf dBm AVG ' ";
$def[1] .= "GPRINT:noiseU:LAST:'%3.0lf dBm LAST\\n' ";

$def[1] .= "LINE1:signalU{$_C_LINE}:'' ";
$def[1] .= "LINE1:noiseU{$_C_LINE} ";


# Define connection graph
#$ds_name[1] = "{$_CCQ['NAME']} {$_AQUALITY['NAME']} {$_ACAPACITY['NAME']}";
$ds_name[2] = "{$_CCQ['NAME']}";
$opt[2] = "--vertical-label 'Percent' --title '{$this->MACRO['DISP_HOSTNAME']} / {$this->MACRO['DISP_SERVICEDESC']} Connection' --lower-limit=0 ";

$def[2]  = "DEF:ccq={$_CCQ['RRDFILE']}:{$_CCQ['DS']}:AVERAGE ";
#$def[2] .= "DEF:aquality={$_AQUALITY['RRDFILE']}:{$_AQUALITY['DS']}:AVERAGE ";
#$def[2] .= "DEF:acapacity={$_ACAPACITY['RRDFILE']}:{$_ACAPACITY['DS']}:AVERAGE ";

$def[2] .= "LINE1:ccq{$_C_CCQ}:'CCQ            ' ";
$def[2] .= "GPRINT:ccq:MIN:'%3.0lf%% MIN ' ";
$def[2] .= "GPRINT:ccq:MAX:'%3.0lf%% MAX ' ";
$def[2] .= "GPRINT:ccq:AVERAGE:'%3.0lf%% AVG ' ";
$def[2] .= "GPRINT:ccq:LAST:'%3.0lf%% LAST\\n' ";

#$def[2] .= "LINE1:aquality{$_C_AQUALITY}:'Airmax Quality ' ";
#$def[2] .= "GPRINT:aquality:MIN:'%3.0lf%% MIN ' ";
#$def[2] .= "GPRINT:aquality:MAX:'%3.0lf%% MAX ' ";
#$def[2] .= "GPRINT:aquality:AVERAGE:'%3.0lf%% AVG ' ";
#$def[2] .= "GPRINT:aquality:LAST:'%3.0lf%% LAST\\n' ";

#$def[2] .= "LINE1:acapacity{$_C_ACAPACITY}:'Airmax Capacity' ";
#$def[2] .= "GPRINT:acapacity:MIN:'%3.0lf%% MIN ' ";
#$def[2] .= "GPRINT:acapacity:MAX:'%3.0lf%% MAX ' ";
#$def[2] .= "GPRINT:acapacity:AVERAGE:'%3.0lf%% AVG ' ";
#$def[2] .= "GPRINT:acapacity:LAST:'%3.0lf%% LAST\\n' ";


# Define rate graph
$ds_name[3] = "{$_TXRATE['NAME']} {$_RXRATE['NAME']}";
$opt[3] = "--vertical-label 'Mbps' --title '{$this->MACRO['DISP_HOSTNAME']} / {$this->MACRO['DISP_SERVICEDESC']} Rate' --lower-limit=0 ";

$def[3]  = "DEF:rxrate={$_RXRATE['RRDFILE']}:{$_RXRATE['DS']}:AVERAGE ";
$def[3] .= "DEF:txrate={$_TXRATE['RRDFILE']}:{$_TXRATE['DS']}:AVERAGE ";

$def[3] .= "LINE1:rxrate{$_C_RXRATE}:'Rx Rate' ";
$def[3] .= "GPRINT:rxrate:MIN:'%3.1lf Mbps MIN ' ";
$def[3] .= "GPRINT:rxrate:MAX:'%3.1lf Mbps MAX ' ";
$def[3] .= "GPRINT:rxrate:AVERAGE:'%3.1lf Mbps AVG ' ";
$def[3] .= "GPRINT:rxrate:LAST:'%3.1lf Mbps LAST\\n' ";

$def[3] .= "LINE1:txrate{$_C_TXRATE}:'Tx Rate' ";
$def[3] .= "GPRINT:txrate:MIN:'%3.1lf Mbps MIN ' ";
$def[3] .= "GPRINT:txrate:MAX:'%3.1lf Mbps MAX ' ";
$def[3] .= "GPRINT:txrate:AVERAGE:'%3.1lf Mbps AVG ' ";
$def[3] .= "GPRINT:txrate:LAST:'%3.1lf Mbps LAST\\n' ";


# Define data load
$ds_name[4] = "{$_WCON['NAME']} {$_LAVG['NAME']}";
$opt[4] = "--vertical-label 'Ps' --title '{$this->MACRO['DISP_HOSTNAME']} / {$this->MACRO['DISP_SERVICEDESC']} Usage' --lower-limit=0 ";

$def[4]  = "DEF:wcon={$_WCON['RRDFILE']}:{$_WCON['DS']}:AVERAGE ";
$def[4] .= "DEF:lavg={$_LAVG['RRDFILE']}:{$_LAVG['DS']}:AVERAGE ";

$def[4] .= "LINE1:wcon{$_C_RXDATA}:'Users Connected' ";
$def[4] .= "GPRINT:wcon:MIN:'%3.1lf Users MIN ' ";
$def[4] .= "GPRINT:wcon:MAX:'%3.1lf Users MAX ' ";
$def[4] .= "GPRINT:wcon:AVERAGE:'%3.1lf Users AVG ' ";
$def[4] .= "GPRINT:wcon:LAST:'%3.1lf Users LAST\\n' ";

$def[4] .= "LINE1:lavg{$_C_TXDATA}:'Load CPU' ";
$def[4] .= "GPRINT:lavg:MIN:'%3.1lf Load MIN ' ";
$def[4] .= "GPRINT:lavg:MAX:'%3.1lf Load MAX ' ";
$def[4] .= "GPRINT:lavg:AVERAGE:'%3.1lf Load AVG ' ";
$def[4] .= "GPRINT:lavg:LAST:'%3.1lf Load LAST\\n' ";

?>
