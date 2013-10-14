
ubnt2nag
========

Nagios plugin for UBNT devices


Main features
=============

* Ruby small script, easy to understand and hack
* Support 'status_cgi' and 'mca_status' commands


Licence
=======

This script is free software; you can redistribute it and/or modify it under the terms of
the GNU Lesser General Public License as published by the Free Software Foundation;
either version 2.1 of the License, or (at your option) any later version.

You should have received a copy of the GNU Lesser General Public License along with this
script; if not, please visit http://www.gnu.org/copyleft/gpl.html for more information.


News
====

12.10.2013 Release 0.2

* x
* y

12.10.2013 Release 0.1

* x
* y


Install complex
===============

	git clone https://github.com/slayer/ubnt2nag.git /var/lib/nagios/github/ubnt2nag
	sudo ln -sf /var/lib/nagios/github/ubnt2nag/check_ubnt.php /usr/share/pnp4nagios/html/templates.dist/check_ubnt.php


Usage
=====

	ubnt2nag -h [user@]host[:port] -u user [-p password] -k ssh_key_file [-v] command


Example Nagios config
=====================

	define command{
	  command_name           check_ubnt
	  command_line           /etc/nagios3/github/ubnt2nag/ubnt2nag -h '$HOSTADDRESS$' -u '$ARG1$' -k '$ARG2$' '$ARG3$'
	}

	define service {
	  use                    generic-service,srv-pnp
	  hostgroup_name         wl-kiev-bs1,wl-kiev-bs2,wl-kiev-bs3
	  service_description    Ubnt Graph
	  check_command          check_ubnt!admin!/etc/nagios3/ssh/kiev_ubiquiti.priv!mca_status
	  normal_check_interval  1
	  retry_check_interval   1
	  notifications_enabled  0
	  notification_interval  0 ; set > 0 if you want to be renotified
	}


Todo notes
==========

	1.Если отсутствует параметр lanSpeed, то писать "Off"
	2.В параметре platform (и других) заменять пробелы на подчеркивания
