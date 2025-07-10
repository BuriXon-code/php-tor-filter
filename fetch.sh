#!/bin/bash
##############################################
# Author: Kamil BuriXon Burek (BuriXon-code) #
# Name: php-tor-filter (c) 2025              #
# Description: A PHP script for filtering    #
#              clients using the Tor network #
#              ( A script that downloads     #
#              lists of Tor IP addresses.)   #
# Version: v 1.0                             #
# Changelog: release                         #
# Todo:                                      #
##############################################
DIR=""
if [ -n "$1" ] && [ $# -eq 1 ]; then
	DIR="$1"
else
    echo "Error: You must specify output directory" >&2
    exit 1
fi
if [ ! -d "$DIR" ]; then
    echo "Error: Target directory does not exist: $DIR" >&2
    exit 2
fi
OUT="${DIR}/tor-nodes.lst"
TEMP=$(mktemp)
> "$TEMP"
curl -s https://check.torproject.org/exit-addresses \
    | awk '/^ExitAddress/ {print $2}' >> "$TEMP"
curl -s https://www.dan.me.uk/torlist/ >> "$TEMP"
curl -s https://raw.githubusercontent.com/firehol/blocklist-ipsets/master/tor_exits.netset \
    | grep -Eo '([0-9]{1,3}\.){3}[0-9]{1,3}' >> "$TEMP"
sort -u "$TEMP" > "$OUT"
rm -f "$TEMP"
echo "[✓] File saved as: $OUT"
echo "[✓] Total unique IPs: $(wc -l < "$OUT")"
