DeleteLeaderboards=php $TRAVIS_BUILD_DIR/tests/DeleteLeaderboards.php
if [ $DeleteLeaderboards = false ]
then
    Exit 2
fi



GetLeaderboards=php $TRAVIS_BUILD_DIR/tests/GetLeaderboards.php
if [ $GetLeaderboards = false ]
then
    Exit 2
fi
read