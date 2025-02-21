# Nuxgame tech task

This is a technical assignment done while applying to Nuxgame / Это техническое задание, выполненное для Nuxgame

## Installation / Установка

To Install and test the project / для установки и тестирования проекта

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

Open in the browser / Открыть в браузере: http://localhost:8000.
