<?php
//##############################################
//# Author: Kamil BuriXon Burek (BuriXon-code) #
//# Name: php-tor-filter (c) 2025              #
//# Description: A PHP script for filtering    #
//#              clients using the Tor network #
//#              ( The main script that filters#
//#              client IP addresses. )        #
//# Version: v 1.0                             #
//# Changelog: release                         #
//# Todo:                                      #
//##############################################
	// path to the file containing known Tor node IPs
	$TOR_LIST_FILE   = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . 'path to tor-nodes.lst file';

	// path to the Tor handler script to include
	$TOR_HANDLER     = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . 'path to tor-handler script';

	// cookie names used to skip repeated checks
	$COOKIE_NON_TOR  = 'non_tor_checked'; // visitor is not using Tor
	$COOKIE_TOR_PASS = 'tor_passed';      // visitor passed through Tor handler

	// if no cookie is set, perform the IP check
	if (!isset($_COOKIE[$COOKIE_NON_TOR]) && !isset($_COOKIE[$COOKIE_TOR_PASS])) {
		$client_ip = $_SERVER['REMOTE_ADDR'];

		// check if the IP list file exists
		if (is_file($TOR_LIST_FILE)) {
			// load list of Tor IPs into an array
			$ips = file($TOR_LIST_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

			// compare visitor IP to the list
			if (in_array($client_ip, $ips, true)) {
				// if it's a Tor IP, run the handler and exit
				include $TOR_HANDLER;
				exit;
			}
		}

		// if IP is not found, set a cookie to skip future checks
		setcookie($COOKIE_NON_TOR, '1', 0, '/');
	}
?>
