### Symfony 4 application
by [Khoerodin](https://khoerodin.id)

#### Configuration

```bash
git clone https://github.com/khoerodin/symfony4.git
cd symfony4
composer install
```

_then config your DB on .env file, and:_

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

#### Run Applicatiion
```bash
php -S 127.0.0.1:8000 -t public
```