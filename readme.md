# PHP MULTIPROCESSING SERVER CHECKER

### A simple application for checking the availability of servers which works in multiprocessing mode(via pcntl module).

How to run:

`docker-compose up -d --build` - to up docker environment

`docker exec -ti ping-php_app_1 php index.php` - to run checker

By default, it works in 5 parallel processes. But you can change it by passing the integer argument to the command I mentioned above. Like this:

`docker exec -ti ping-php_app_1 php index.php 10` - now the script will handle 10 processes simultaneously.



