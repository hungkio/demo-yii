#### First time only
* Copy preference file
```
[/***/docker-laravel]
$ cp .env.example .env
```
※ If the port is batting, change the value of the copied .env.

* Start container
```
[/***/docker-laravel]
$ docker-compose up -d --build
```

* Database migrate
```
[/***/docker-laravel]
$ docker-compose exec app bash
[/var/www/app]
$ composer install
$ cp .env.example .env
$ php artisan key:generate
$ php artisan config:cache
$ php artisan migrate
$ npm install
$ npm run dev
```

※ The development location is the source in the _/src_ folder directly under.
- URL：http://127.0.0.1:9001

#### After the second time
```
[/***/docker-laravel]
$ docker-compose up -d
```

#### When deleting
```
[/***/docker-laravel]
$ docker-compose down
```

#### When deleting the entire image
```
[/***/docker-laravel]
$ docker-compose down --volumes --rmi all
```

#### DB connection

* Access the DB locally with a tool such as MySQL Workbench
* Set the following information in the tool.

| Key | Value |
| --- | --- |
| Host | 127.0.0.1 |
| Port | 33061 |
| User | root |
| Pass | secret |
