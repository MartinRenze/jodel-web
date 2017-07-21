# Due to request from Jodel I quit developing Jodelblue :-(

## Message from Jodel Venture GmbH
>Leider muss ich dir mitteilen, dass wir derzeit keine Pläne haben Projekte wie JodelBlue zu unterstützen, 
>da diese leider zu oft dazu missbraucht werden, gegen unsere Community Guidelines zu verstoßen und dadurch 
>Jodel und seiner Community mehr schaden als Gutes tun.
>Deshalb würden wir dich an dieser Stelle bitten von der Weiterentwicklung von JodelBlue abzusehen.


# jodel-web [![Build Status](https://scrutinizer-ci.com/g/mmainstreet/jodel-web/badges/build.png?b=master)](https://scrutinizer-ci.com/g/mmainstreet/jodel-web/build-status/master) [![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/mmainstreet/jodel-web/issues)

## Demo ##
https://www.jodelblue.com/

## Setting up jodel-web ##
1. Create MySql Database
2. Edit config/config-sample.ini.php and insert MySQL login credentials
3. Rename config-sample.ini.php to config.ini.php
4. Create Jodel account (see below)
5. Done

## How to Use##
### Create Jodel account ###
1. visit ./admin.php?pw=PasswordYouSetInConfig
2. click on 'Create new Account'

## Requirements ##
+ Web server (tested on Apache/2.4.18 (Ubuntu))
+ PHP 6 or PHP 7.0 (tested on 7.0.8-3)
+ MySQL (tested on 5.7.16-0)
+ jQuery (tested on 2.0.2 (already included)) 

### Optional ###
+ HTTPS (deactivate by deleting .htaccess)

### Thanks to [Bambi-pa-hal-is](https://github.com/Bambi-pa-hal-is), [Loewe1000](https://github.com/Loewe1000), [LauretBernd](https://github.com/LauertBernd) and [Christian Fibich](https://bitbucket.org/cfib90/) ###
