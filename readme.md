SIMPLE ENTRY SYSTEM WITCH CONNECTIN INTO SCHOOL SYSTEM EDOOKIT
==============================================================
next in Czech language

Jenoduchý vstupní systém s napojením na školní infrmační systém EDOOKIT
=======================================================================
programovaci jazyk:
    webova aplikace - PHP (pouzity framework Nette)
    ukladani dat    - MySQL
    komunikace se RFID cteckami - python

Instalace:
----------
- prekopirovanim do weboveho serveru s podporou PHP a MySQL (nejlepe Apache2)
- app/config/config.local.neon - nastavit pritupove udaje k MySQL databasi
- do MySQL DB nahrat zakladni strukturu database ze souhor access_system.sql
- zajititi spusteni souboru:
     bin/door_reader.py - pro vycitani RFID ctecek pripojenych k RS485 sbernici
     bin/edookit_sync.sh - pro zasilani prochodu do skolniho systemu EDOOKIT

Zabespeceni:
------------
je reseno pomoci firewallu a nastaveni weboveho serveru - neni soucasti projektu


License
-------
- Nette: New BSD License or GPL 2.0 or 3.0 (https://nette.org/license)
- Adminer: Apache License 2.0 or GPL 2 (https://www.adminer.org)
