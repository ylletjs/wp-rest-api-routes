deps:
	composer install

lint:
	vendor/bin/phpcs -s --extensions=php --standard=phpcs.xml src/