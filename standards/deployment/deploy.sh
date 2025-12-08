cd /home/forge/core.goharafarinan.com

git add .
git stash
git reset --hard
git fetch origin $FORGE_SITE_BRANCH
git pull origin $FORGE_SITE_BRANCH

$FORGE_COMPOSER install

npm install

echo '--- Start npm run build ---'
npm run build
echo '--- End npm run build ---'

# Prevent concurrent php-fpm reloads...
touch /tmp/fpmlock 2>/dev/null || true
( flock -w 10 9 || exit 1
    echo 'Reloading PHP FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9</tmp/fpmlock

if [ -f artisan ]; then
    $FORGE_PHP artisan optimize
    $FORGE_PHP artisan migrate --force
    $FORGE_PHP artisan fetch:events
    $FORGE_PHP artisan fetch:entities
    $FORGE_PHP artisan fetch:enums
fi
