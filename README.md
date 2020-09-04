MindGeek Test
---

## Installation
- `git clone https://github.com/shmocs/mgtest`
- `cd mgtest`
- `./install.sh`
- `docker-compose exec php74 bash`
- `composer install`
- `php artisan key:generate`


## Available commands
**Run tests**
 - `vendor/bin/phpunit`
![demo](docs/img/phpunit.png)

**Apply migrations. 
Can be run multiple times by rolling all over back and then migrate**
 - `php artisan migrate:rollback` - not needed first time  
![demo](docs/img/migrate-rollback.png)
 
 - `php artisan migrate`
![demo](docs/img/migrate.png)  

**Start RabbitMq consumer, so it will be ready to parse/cache images inserted in database**
- `php artisan imagemq:consumer`
![demo](docs/img/consumer.png)

**Proceed the import**
- `php artisan import`
![demo](docs/img/import.png)
- when items import id ready - it will launch the producer command (to put all unprocessed images in queue for caching)
- site should be ready/visible/populated now - even if not all images are yet cached  

**In the meantime consumer started to process messages**
![demo](docs/img/consumer-at-work.png)
- unavailable images & available images - are all marked as processed, so they won't be processed again at subsequent commands 
- site is serving only cached images 


**Producer can be executed again - it will deal only with unprocessed images**
![demo](docs/img/produce-again.png)
- if import is launched again - it will reset processed flag and producer will enqueue again images to be processed

