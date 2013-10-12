
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
	#
	cd ubnt2nag
	cp check_ubnt_graph.php /usr/share/pnp4nagios/html/templates.dist/
	cp check_ubnt_* /etc/nagios3/script/

or

	cd /etc/nagios3/script
	ln -sf /var/lib/nagios/github/ubnt2nag/check_ubnt_graph check_ubnt_graph
	ln -sf /var/lib/nagios/github/ubnt2nag/check_ubnt_inform check_ubnt_inform
	#
	cd /usr/share/pnp4nagios/html/templates.dist
	ln -sf /var/lib/nagios/github/ubnt2nag/check_ubnt_graph.php check_ubnt_graph.php

Todo notes
==========

	returnValues = { 'OK' : 0, 'WARNING' : 1, 'CRITICAL' : 2, 'UNKNOWN' : 3 }
