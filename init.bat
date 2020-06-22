@echo off
echo[
echo =======================================================
echo This data will be written to the config_local.php file.
echo You can change them at any time.
echo =======================================================
echo Enter local name or IP to DataBase (localhost default):
SET /P "host=" || SET "host=localhost"

echo Enter user name (sysdba default):
SET /P "user=" || SET "user=sysdba"

echo Enter user password (1 default):
SET /P "password=" || SET "password=1"

echo ^<?php ^$AptekaList[^$AptIndex] = new Apteka("Аптека 1", "Аптека №1", "ap1", "%host%", "ds", "%user%", "%password%", "вул. Зелена, 31", "430-400", 1.5, 0); $AptIndex=$AptIndex+1;  ?^> > html/config_local.php

echo[
echo Web server ready!!!