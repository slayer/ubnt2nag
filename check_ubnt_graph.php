<?php
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
$_CCQ       = $this->DS[4];
$_AQUALITY  = $this->DS[5];
$_ACAPACITY = $this->DS[6];
$_TXRATE    = $this->DS[7];
$_RXRATE    = $this->DS[8];

# Calculations
$_SIGMIN = min ($_SIGNAL['MIN'], $_SIGNAL0['MIN'], $_SIGNAL1['MIN'], $_NOISE['MIN']);
$_SIGMAX = max ($_SIGNAL['MAX'], $_SIGNAL0['MAX'], $_SIGNAL1['MAX'], $_NOISE['MAX']);

# Define signal graph
$ds_name[0] = "{$_NOISE['NAME']} {$_SIGNAL['NAME']} {$_SIGNAL0['NAME']} {$_SIGNAL1['NAME']}";
$opt[0] = "--vertical-label 'dBm' --title '{$this->MACRO['DISP_HOSTNAME']} / {$this->MACRO['DISP_SERVICEDESC']} Signal' --alt-y-grid ";

$def[0]  = "DEF:noise={$_NOISE['RRDFILE']}:{$_NOISE['DS']}:AVERAGE ";
$def[0] .= "DEF:signal={$_SIGNAL['RRDFILE']}:{$_SIGNAL['DS']}:AVERAGE ";
$def[0] .= "DEF:signal0={$_SIGNAL0['RRDFILE']}:{$_SIGNAL0['DS']}:AVERAGE ";
$def[0] .= "DEF:signal1={$_SIGNAL1['RRDFILE']}:{$_SIGNAL1['DS']}:AVERAGE ";

# If noise or signal equal 0 then the link was down
$def[0] .= "CDEF:noiseU=noise,0,EQ,UNKN,noise,IF ";
$def[0] .= "CDEF:signalU=signal,0,EQ,NEGINF,signal,IF ";

# If signal0 or signal1 equal -96 then the link was down
$def[0] .= "CDEF:signal0U=signal0,-96,EQ,UNKN,signal0,IF ";
$def[0] .= "CDEF:signal1U=signal1,-96,EQ,UNKN,signal1,IF ";

# Drop values to -infinity for filling graph
$def[0] .= "CDEF:noiseI=noiseU,UN,UNKN,NEGINF,IF ";
$def[0] .= "CDEF:signalI=signalU,UN,UNKN,NEGINF,IF ";

# Plot values
$def[0] .= "LINE1:signalU{$_C_SIGNAL}:'Signal        ' ";
$def[0] .= "AREA:signalI{$_C_SIGNAL}:'':STACK ";
$def[0] .= "GPRINT:signalU:MIN:'%3.0lf dBm MIN ' ";
$def[0] .= "GPRINT:signalU:MAX:'%3.0lf dBm MAX ' ";
$def[0] .= "GPRINT:signalU:AVERAGE:'%3.0lf dBm AVG ' ";
$def[0] .= "GPRINT:signalU:LAST:'%3.0lf dBm LAST\\n' ";

$def[0] .= "LINE1:signal0U{$_C_SIGNAL0}:'Signal Chain 0' ";
$def[0] .= "GPRINT:signal0U:MIN:'%3.0lf dBm MIN ' ";
$def[0] .= "GPRINT:signal0U:MAX:'%3.0lf dBm MAX ' ";
$def[0] .= "GPRINT:signal0U:AVERAGE:'%3.0lf dBm AVG ' ";
$def[0] .= "GPRINT:signal0U:LAST:'%3.0lf dBm LAST\\n' ";

$def[0] .= "LINE1:signal1U{$_C_SIGNAL1}:'Signal Chain 1' ";
$def[0] .= "GPRINT:signal1U:MIN:'%3.0lf dBm MIN ' ";
$def[0] .= "GPRINT:signal1U:MAX:'%3.0lf dBm MAX ' ";
$def[0] .= "GPRINT:signal1U:AVERAGE:'%3.0lf dBm AVG ' ";
$def[0] .= "GPRINT:signal1U:LAST:'%3.0lf dBm LAST\\n' ";

$def[0] .= "LINE1:noiseU{$_C_NOISE}:'Noise         ' ";
$def[0] .= "AREA:noiseI{$_C_NOISE}:'':STACK ";
$def[0] .= "GPRINT:noiseU:MIN:'%3.0lf dBm MIN ' ";
$def[0] .= "GPRINT:noiseU:MAX:'%3.0lf dBm MAX ' ";
$def[0] .= "GPRINT:noiseU:AVERAGE:'%3.0lf dBm AVG ' ";
$def[0] .= "GPRINT:noiseU:LAST:'%3.0lf dBm LAST\\n' ";

$def[0] .= "LINE1:signalU{$_C_LINE}:'' ";
$def[0] .= "LINE1:noiseU{$_C_LINE} ";

# Define connection graph
$ds_name[1] = "{$_CCQ['NAME']} {$_AQUALITY['NAME']} {$_ACAPACITY['NAME']}";
$opt[1] = "--vertical-label 'Percent' --title '{$this->MACRO['DISP_HOSTNAME']} / {$this->MACRO['DISP_SERVICEDESC']} Connection' --lower-limit=0 ";

$def[1]  = "DEF:ccq={$_CCQ['RRDFILE']}:{$_CCQ['DS']}:AVERAGE ";
$def[1] .= "DEF:aquality={$_AQUALITY['RRDFILE']}:{$_AQUALITY['DS']}:AVERAGE ";
$def[1] .= "DEF:acapacity={$_ACAPACITY['RRDFILE']}:{$_ACAPACITY['DS']}:AVERAGE ";

$def[1] .= "LINE1:ccq{$_C_CCQ}:'CCQ            ' ";
$def[1] .= "GPRINT:ccq:MIN:'%3.0lf%% MIN ' ";
$def[1] .= "GPRINT:ccq:MAX:'%3.0lf%% MAX ' ";
$def[1] .= "GPRINT:ccq:AVERAGE:'%3.0lf%% AVG ' ";
$def[1] .= "GPRINT:ccq:LAST:'%3.0lf%% LAST\\n' ";

$def[1] .= "LINE1:aquality{$_C_AQUALITY}:'Airmax Quality ' ";
$def[1] .= "GPRINT:aquality:MIN:'%3.0lf%% MIN ' ";
$def[1] .= "GPRINT:aquality:MAX:'%3.0lf%% MAX ' ";
$def[1] .= "GPRINT:aquality:AVERAGE:'%3.0lf%% AVG ' ";
$def[1] .= "GPRINT:aquality:LAST:'%3.0lf%% LAST\\n' ";

$def[1] .= "LINE1:acapacity{$_C_ACAPACITY}:'Airmax Capacity' ";
$def[1] .= "GPRINT:acapacity:MIN:'%3.0lf%% MIN ' ";
$def[1] .= "GPRINT:acapacity:MAX:'%3.0lf%% MAX ' ";
$def[1] .= "GPRINT:acapacity:AVERAGE:'%3.0lf%% AVG ' ";
$def[1] .= "GPRINT:acapacity:LAST:'%3.0lf%% LAST\\n' ";

# Define rate graph
$ds_name[2] = "{$_TXRATE['NAME']} {$_RXRATE['NAME']}";
$opt[2] = "--vertical-label 'Mbps' --title '{$this->MACRO['DISP_HOSTNAME']} / {$this->MACRO['DISP_SERVICEDESC']} Rate' --lower-limit=0 ";

$def[2]  = "DEF:rxrate={$_RXRATE['RRDFILE']}:{$_RXRATE['DS']}:AVERAGE ";
$def[2] .= "DEF:txrate={$_TXRATE['RRDFILE']}:{$_TXRATE['DS']}:AVERAGE ";

$def[2] .= "LINE1:rxrate{$_C_RXRATE}:'Rx Rate' ";
$def[2] .= "GPRINT:rxrate:MIN:'%3.1lf Mbps MIN ' ";
$def[2] .= "GPRINT:rxrate:MAX:'%3.1lf Mbps MAX ' ";
$def[2] .= "GPRINT:rxrate:AVERAGE:'%3.1lf Mbps AVG ' ";
$def[2] .= "GPRINT:rxrate:LAST:'%3.1lf Mbps LAST\\n' ";

$def[2] .= "LINE1:txrate{$_C_TXRATE}:'Tx Rate' ";
$def[2] .= "GPRINT:txrate:MIN:'%3.1lf Mbps MIN ' ";
$def[2] .= "GPRINT:txrate:MAX:'%3.1lf Mbps MAX ' ";
$def[2] .= "GPRINT:txrate:AVERAGE:'%3.1lf Mbps AVG ' ";
$def[2] .= "GPRINT:txrate:LAST:'%3.1lf Mbps LAST\\n' ";

# Define data graph
$ds_name[3] = "{$_TXDATA['NAME']} {$_RXDATA['NAME']}";
$opt[3] = "--vertical-label 'Mbps' --title '{$this->MACRO['DISP_HOSTNAME']} / {$this->MACRO['DISP_SERVICEDESC']} Speed' --lower-limit=0 ";

$def[3]  = "DEF:rxdata={$_RXDATA['RRDFILE']}:{$_RXDATA['DS']}:AVERAGE ";
$def[3] .= "DEF:txdata={$_TXDATA['RRDFILE']}:{$_TXDATA['DS']}:AVERAGE ";

$def[3] .= "LINE1:rxdata{$_C_RXDATA}:'Rx Data' ";
$def[3] .= "GPRINT:rxdata:MIN:'%3.1lf Mbps MIN ' ";
$def[3] .= "GPRINT:rxdata:MAX:'%3.1lf Mbps MAX ' ";
$def[3] .= "GPRINT:rxdata:AVERAGE:'%3.1lf Mbps AVG ' ";
$def[3] .= "GPRINT:rxdata:LAST:'%3.1lf Mbps LAST\\n' ";

$def[3] .= "LINE1:txdata{$_C_TXDATA}:'Tx Data' ";
$def[3] .= "GPRINT:txdata:MIN:'%3.1lf Mbps MIN ' ";
$def[3] .= "GPRINT:txdata:MAX:'%3.1lf Mbps MAX ' ";
$def[3] .= "GPRINT:txdata:AVERAGE:'%3.1lf Mbps AVG ' ";
$def[3] .= "GPRINT:txdata:LAST:'%3.1lf Mbps LAST\\n' ";


?>
