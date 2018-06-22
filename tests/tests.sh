#!/bin/bash
#
# Travis CI Tests Script
#
# Justin Back <jb@justinback.com>



# Testing DeleteLeaderboards.php
# However, setting chmod first!
chmod +x $TRAVIS_BUILD_DIR/tests/DeleteLeaderboards.php
DeleteLeaderboards=$(php $TRAVIS_BUILD_DIR/tests/DeleteLeaderboards.php)
if [ $DeleteLeaderboards = false ]
then
    exit 2
fi


chmod +x $TRAVIS_BUILD_DIR/tests/GetLeaderboards.php
GetLeaderboards=$(php $TRAVIS_BUILD_DIR/tests/GetLeaderboards.php)
if [ $GetLeaderboards = false ]
then
    exit 2
fi
read