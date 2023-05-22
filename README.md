
# DOCKER CONTAINER BUILD

* Don't forget to run your Docker Desktop 



## Run containers

Go to project path

```bash
  your_path/laravel
```

Build project

```bash
  docker-compose up --build -d
```

After a while, three containers where build and run. You can check their state by next command. If you see all three containers running with given ports, everything is done and you can skip all next steps 

```bash
  docker ps -a
```

In case that one of the containers is not running, you can try this command. Container_id can be also replaced by container name instead

```bash
  docker start <container_id>
```




## Features

- Installed inside container:
    - PHP 8.1 with Apache web server
    - pdo_mysql PHP extension
    - Git
    - Zip and unzip utilities
    - Node.js 16.x
    - Composer (PHP dependency manager)
    - Laravel framework
    - Laravel UI package
    - Bootstrap front-end framework
    - NPM packages (dependencies for Laravel UI)
    - Laravel encryption key
    - Database migration scripts
    - Artisan command-line tool
    - Laravel development server (listening on port 8000)

- MyPhpAdmin running and accessed on port 8080
- One problem persist: Project APP is running at port :8000 inside project and also this port is exposed to localhost, but eventualy you are not able to see app runing outside the container from your localhost. We couldn't figure it out, if it's a firewall issue or what could be causing this problem


