#!/bin/bash
#
# Travis CI Tests Script
#
# Justin Back <jb@justinback.com>
set -e
echo "Running CI Tests..."

chmod +x $TRAVIS_BUILD_DIR/tests/leaderboards/FindOrCreateLeaderboard.php
FindOrCreateLeaderboard=$(php $TRAVIS_BUILD_DIR/tests/leaderboards/FindOrCreateLeaderboard.php)
if [ $FindOrCreateLeaderboard != true ]
then
    echo $FindOrCreateLeaderboard
    exit 2
fi
echo "Leaderboards created successfully, test passed"

chmod +x $TRAVIS_BUILD_DIR/tests/leaderboards/DeleteLeaderboard.php
DeleteLeaderboard=$(php $TRAVIS_BUILD_DIR/tests/leaderboards/DeleteLeaderboard.php)
if [ $DeleteLeaderboard != true ]
then
    echo $DeleteLeaderboard
    exit 2
fi
echo "Leaderboards deleted successfully, test passed"


chmod +x $TRAVIS_BUILD_DIR/tests/leaderboards/GetLeaderboardsForGame.php
GetLeaderboardsForGame=$(php $TRAVIS_BUILD_DIR/tests/leaderboards/GetLeaderboardsForGame.php)
if [ $GetLeaderboardsForGame != true ]
then
    echo $GetLeaderboardsForGame
    exit 2
fi
echo "Leaderboards retrieved successfully, test passed"


chmod +x $TRAVIS_BUILD_DIR/tests/leaderboards/ResetLeaderboard.php
ResetLeaderboard=$(php $TRAVIS_BUILD_DIR/tests/leaderboards/ResetLeaderboard.php)
if [ $ResetLeaderboard != true ]
then
    echo $ResetLeaderboard
    exit 2
fi
echo "Leaderboards reset successfully, test passed"



exit 0