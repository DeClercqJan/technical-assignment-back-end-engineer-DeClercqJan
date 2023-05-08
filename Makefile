install:
	composer install
	symfony console lexik:jwt:generate-keypair --overwrite

run:
	symfony console doctrine:database:drop --force
	symfony console doctrine:database:create
	symfony console doctrine:migrations:migrate
	symfony console doctrine:fixtures:load
	symfony server:start

test:
	symfony console --env=test doctrine:database:drop --force
	symfony console --env=test doctrine:database:create
	symfony console --env=test doctrine:migrations:migrate
	symfony console --env=test doctrine:fixtures:load
	symfony php bin/phpunit