### Local Development
When you want to develop locally you will run the Laravel app and connect to your Dockerized database and redis.

### Run using Docker
When you're finished with the development to run the app with Docker to the following
1. Change the `DB_HOST` env variable to `db` (db is the container service name)
2. Change the `REDIS_HOST` env variable to `redis` (redis is the container service name)
3. Build the containers
> docker compose up --build --detach
4. Go to `http://localhost`

### Going to production!
There are minimal changes needed when going to production.

You should change the nginx.conf to match your website URL and add SSL so that you can have an encrypted connection (HTTPS). Always USE PORT 443.

# PINT
* composer require --dev laravel/pint
* ./vendor/bin/pint --parallel

# SERVE
* php artisan serve --host=0.0.0.0 --port=80
