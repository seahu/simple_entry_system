SIMPLE ENTRY SYSTEM WITCH CONNECTIN INTO SCHOOL SYSTEM EDOOKIT
==============================================================
next in Czech language

Jenoduchy vstupn√≠ syst√ ©ms napojen√≠m na ≈°kolni infrmaƒn√≠ syst√©m EDO
=========================================================================
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



Nette Sandbox
=============

Sandbox is a pre-packaged and pre-configured Nette Framework application
that you can use as the skeleton for your new applications.

[Nette](https://nette.org) is a popular tool for PHP web development.
It is designed to be the most usable and friendliest as possible. It focuses
on security and performance and is definitely one of the safest PHP frameworks.

License
-------
- Nette: New BSD License or GPL 2.0 or 3.0 (https://nette.org/license)
- Adminer: Apache License 2.0 or GPL 2 (https://www.adminer.org)
