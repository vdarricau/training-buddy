symfony console doctrine:database:drop --force --env=test
symfony console doctrine:database:create --env=test
symfony console doctrine:schema:update --force --env=test
symfony console doctrine:fixtures:load -n --env=test
symfony php bin/phpunit
