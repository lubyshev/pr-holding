# Тестовое задание для PR Holding.

## Особенности реализации.

* Реализовано с поддержкой типов php-7.4.
* Не стал заморачиваться с `ajax`.

## Непонятности.

Были описаны следующие условия:

* Дата падения (устанавливается при падении объекта с дерева)
* После лежания 5 часов - портится.
* ...  которые на этой же странице можно сгенерировать в случайном
  кол-ве соответствующей кнопкой.

Не указано, что яблоко уже может быть на земле. Непонятно, как тогда
Вы будете проверять? Неужели ждать пять часов? )))

Сделаю при генерации так, что часть яблок будет уже упавшей,
со сроком давности за 10-15 минут до загнивания.

## Запуск.

* Клонировать [репозитарий](https://github.com/lubyshev/pr-holding).
* Обновить `composer`.
* Осуществить настройку БД.
* Запустить миграции.
* Добавить RBAC:
```shell script
yii migrate --migrationPath=@yii/rbac/migrations
```
* Настроить `apache`/`nginx`.
* Перейти на сайт. Зарегестрироваться. Войти.
* Перейти в раздел `/admin`.
* Нажать кнопку "Собирать урожай".
* Ввести пароль "adam".
* Можно тестировать.
