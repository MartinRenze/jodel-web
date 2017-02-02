# jodel-web [![Build Status](https://scrutinizer-ci.com/g/mmainstreet/jodel-web/badges/build.png?b=master)](https://scrutinizer-ci.com/g/mmainstreet/jodel-web/build-status/master) [![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/mmainstreet/jodel-web/issues)


## Demo ##
https://www.jodelblue.com/

## ToDo-List ##
### Frontend ###
- [ ] Fix scroll down bug
- [ ] change Font
- [ ] Scan with Minion (https://github.com/mozilla/minion-vm/)
- [ ] Add hashtag-support
- [ ] Send img
- [ ] Add geolocate button
- [ ] Check with acunetix pro
- [ ] Add Login
- [ ] Fix Link upvote marker
- [ ] Add loading icon
- [ ] Html cache
- [ ] Flag jodel
- [ ] Account views
- [ ] Error handling
- [ ] Share buttons
- [ ] Fix: Vote has wrong view
- [ ] Options page
- [ ] Check if jodel is available or redirect home
- [ ] Rotate img function

### Backend ###
- [ ] Check growing DB
- [ ] implement log system
- [ ] fix errorlog
- [ ] Get Key from APK automatically

## Setting up jodel-web ##
1. Create MySql Database
2. Edit config/config-sample.ini.php and insert MySQL login credentials
3. Rename config-sample.ini.php to config.ini.php
4. Create Jodel account (see below)
5. Done


## How to Use##
### Create Jodel account ###
1. visit ./admin.php
2. click on 'Create new Account'


## Requirements ##
+ Web server (tested on Apache/2.4.18 (Ubuntu))
+ PHP 6 or PHP 7.0 (tested on 7.0.8-3)
+ MySQL (tested on 5.7.16-0)
+ jQuery (tested on 2.0.2 (already included)) 

### Optional ###
+ HTTPS (deactivate by deleting .htaccess)

### Thanks to [LauretBernd](https://github.com/LauertBernd) and [Christian Fibich](https://bitbucket.org/cfib90/)###
