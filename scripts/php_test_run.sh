symfony console doctrine:database:drop --force
symfony console doctrine:database:create
symfony console doctrine:schema:update --force
symfony php bin/phpunit
