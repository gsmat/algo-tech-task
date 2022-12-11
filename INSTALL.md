# Quick tutorial for project

Clone this Repository
```sh
git clone https://github.com/gsmat/algo-tech-task.git folder-name
```

Create the .env file
```sh
cd folder-name/
cp .env.example .env
```


Update environment variables in .env
```dosini
DB_CONNECTION=db_type
DB_HOST=host_name
DB_PORT=port
DB_DATABASE=database_name
DB_USERNAME=username
DB_PASSWORD=password
```


Create tables with migration command
```sh
php artisan migrate
```


Seed dummy data to the tables
```sh
php artisan db:seed
```


Install dependencies with composer command to the  project 
```sh
composer install
```

Generate the Laravel project key

```sh
php artisan key:generate
```

Start the project

```sh
php artisan serve
```

Database BackUp command ( If mysqldump command defined on the System Variables then run)

```sh
php artisan db:backup
```

Go to the project in browser
[http://localhost:8000](http://localhost:8000)
