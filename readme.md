# Тестовое задание

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nighten/testwork/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nighten/testwork/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/nighten/testwork/badges/build.png?b=master)](https://scrutinizer-ci.com/g/nighten/testwork/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/nighten/testwork/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

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
Документация на API и тест запросов по роуту: ```http://testwork.local:8089/doc/api/```

Для авторизации используется basic_auth
Логин: ```admin@test.ru```. Пароль: ```12345```
 

