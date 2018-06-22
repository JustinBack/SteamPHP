#!/bin/bash
#
# Travis CI Tests Script
#
# Justin Back <jb@justinback.com>


chmod +x $TRAVIS_BUILD_DIR/tests/CreateLeaderboards.php
CreateLeaderboards=$(php $TRAVIS_BUILD_DIR/tests/CreateLeaderboards.php)
if [ $CreateLeaderboards != true ]
then
    echo $CreateLeaderboards
    exit 2
fi


chmod +x $TRAVIS_BUILD_DIR/tests/DeleteLeaderboards.php
DeleteLeaderboards=$(php $TRAVIS_BUILD_DIR/tests/DeleteLeaderboards.php)
if [ $DeleteLeaderboards != true ]
then
    echo $DeleteLeaderboards
    exit 2
fi


chmod +x $TRAVIS_BUILD_DIR/tests/GetLeaderboards.php
GetLeaderboards=$(php $TRAVIS_BUILD_DIR/tests/GetLeaderboards.php)
if [ $GetLeaderboards != true ]
then
    echo $GetLeaderboards
    exit 2
fi
