
ubnt2nag
========

Usage:

	ubnt2nag -h host[:port] -u user -p password -v command

Where command is status_cgi or mca_status


Install complex
===============

	mkdir -p /var/lib/nagios/github /etc/nagios3/script
	cd /var/lib/nagios/github
	git clone https://github.com/slayer/ubnt2nag.git
	ln -sf /var/lib/nagios/github/ubnt2nag/ubnt2nag /etc/nagios3/script/ubnt2nag
	ln -sf /var/lib/nagios/github/ubnt2nag/check_ubnt_graph.php /usr/share/pnp4nagios/html/templates.dist/check_ubnt_graph.php

Todo notes
==========

	returnValues = { 'OK' : 0, 'WARNING' : 1, 'CRITICAL' : 2, 'UNKNOWN' : 3 }
	
	description="Nagios plugin for UBNT devices"
	
	# Data sources
	$_RXDATA    = $this->DS[0];
	$_TXDATA    = $this->DS[1];
	$_SIGNAL    = $this->DS[2];
	$_NOISE     = $this->DS[3];
	$_TXRATE    = $this->DS[4];
	$_RXRATE    = $this->DS[5];
	$_CCQ       = $this->DS[6];
	$_AQUALITY  = $this->DS[7];
	$_ACAPACITY = $this->DS[8];