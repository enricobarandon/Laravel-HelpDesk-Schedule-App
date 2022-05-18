### Installation and Configuration
 
1.) Install php packages
```
composer install
```

2.) Get the required node modules:
```
npm install
```

3.) copy configuration template .env.example to .env
```
cp .env.example .env
```

4.) set .env DB credentials and the following configuration
```
DB_DATABASE=yourdbname
DB_USERNAME=mysqluser
DB_PASSWORD=mysqlpass
```

5.) Set the key that Laravel will use when doing encryption
```
php artisan key:generate
```

6.) Run migrations to create the database tables
```
php artisan migrate
```

7.) Seed the database with default records
```
php artisan db:seed
```