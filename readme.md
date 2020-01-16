# Тестовое задание

## Краткое описание
Необходимо создать RESTfull API сервис для новостного мобильного приложения.

[Полное описание](doc/task.md)

## Запуск в dev режиме
* Запуск docker контейнеров:  ```docker-compose build``` ```docker-compose up -d```

* Установка зависимостей композером
```docker run --volume ${PWD}:/app composer install```

* Выполнение миграции БД
```docker-compose run php php bin/console doctrine:migrations:migrate```

* Заполнение БД тестовыми данными
```docker-compose run php php bin/console doctrine:fixtures:load```

* Добавляем в `/etc/hosts` запись: `127.0.0.1	testwork.local`

## Использование API
Документация доступна по роуту: ```/doc/api/```

Для авторизации используется basic_auth
Логин: ```admin@test.ru```. Пароль: ```12345```
 

