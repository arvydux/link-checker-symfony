# Link checker app made with Symfony

## Setup
- `git clone https://github.com/arvydux/link-checker-symfony.git`
- `cd link-checker-symfony`
- `docker-compose up -d`
- `composer install`
- `symfony console doctrine:database:create`
- `symfony console doctrine:migrations:migrate`
- `symfony server:start -d`

Now that all containers are up, access `https://127.0.0.1:8000/link` on your favorite browser

## Questions and Improvements

For any question or emprovement please send an e-mail to Arvydas Kavaliauskas [arvydas.kavaliauskas83@gmail.com](mailto:arvydas.kavaliauskas83@gmail.com).
