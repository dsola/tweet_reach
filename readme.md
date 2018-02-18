## Wonderkind Test

### How to run the application

This application is the implementation of an assessment defined by the company Wonderkind.

You can execute a local server using the following command:
```bash
php artisan serve
```
This artisan command starts a web server in the port 8000 and serves the content implemented on this project directory.

### How to run the tests
You can execute the tests as well using the following command:
```bash
vendor/bin/phpunit tests/Unit/
```

### Configuration
You can configure the Twitter credentials and the Cache parameters using the .env file. 
It's mandatory to define the following parameters:
```
TWITTER_CONSUMER_KEY=
TWITTER_CONSUMER_SECRET=
TWITTER_ACCESS_TOKEN=
TWITTER_ACCESS_TOKEN_SECRET=
```
