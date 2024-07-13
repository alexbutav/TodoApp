# Docker compose setup

Just a simple list of steps that will help you to start docker environment

## Start containers

```sh
docker compose build --no-cache
```

And then

```sh
docker compose up
```

## Install php dependencies

Enter php-fpm container (it has installed composer)

```sh
docker exec -it todo-php /bin/sh
```

Install dependencies

```sh
composer install
```

## Add domain name to your host's machine hosts file

Since compose network configured to use a specfic subnet (192.168.100.0/24) and every container has a specified static ip address
We must add configured domain name and container ip to hosts file to be able to access app from host machine

```txt
192.168.100.2   todo.me
```
