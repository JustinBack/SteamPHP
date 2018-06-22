pwd
echo $TRAVIS_BUILD_DIR
php -r "echo __DIR__;"
php $TRAVIS_BUILD_DIR/tests/DeleteLeaderboards.php
php $TRAVIS_BUILD_DIR/tests/GetLeaderboards.php