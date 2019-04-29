#!/bin/bash

# script pro synchronizaci vstupniho systemu se systemem edookit (synchronizuje pouze ucty studentu)
url="http://localhost/access/edookit/pruchody-sync"

while [  1 -eq 1 ]; do
    wget -O/dev/null -q $url
    #pockej 5 min.
    sleep 300
done
