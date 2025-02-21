# Nuxgame tech task

This is a technical assignment done while applying to Nuxgame

## Installation

To Install and test the project

```bash
  git clone https://github.com/arakhachatryan093/nuxgame.git
  cd nuxgame
  cp .env.example .env
  composer install
  touch database/database.sqlite
  php artisan key:generate
  php artisan migrate
  php artisan serve
```

Open in the browser: http://localhost:8000.
