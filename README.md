## Name
Vending Machine System

## Setup Guide for Laravel
```
git clone https://github.com/Htet-Htet-Oo-Wai/vending-machine.git
cd into cloned project folder
cd src
run => composer install
change DB section with your local MySQL database setting in database.php
create new database in your local MySQL
run => php database/migrations/migrate.php
run => php -S localhost:8000 -t public
can be accessed at http://localhost:8000/
```

## Login credentials for admin dashboard
```
email => admin@example.com
password => password
```

## Login credentials for customer dashboard
```
email => customer@example.com
password => password
```

## Versions I have used
- PHP => ^8.1.25
- MySQL => 8.0
