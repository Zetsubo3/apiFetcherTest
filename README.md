### API Data Fetch
Приложение для выгрузки данных из внешнего API (orders, sales, stocks, incomes) в MySQL.

### Установка
#### Клонирование репозитория
- git clone
- cd project-folder
#### Установка зависимостей
- composer install
#### Копирование конфига окружения
- cp .env.example .env
##### ВАЖНО:
- в env.example указано QUEUE_CONNECTION=sync.
Синхронная работа джобов связана с тем, что база данных на бесплатном хостинге не
поддерживает параллельную работу воркеров. Если будете использовать другую бд,
можно указать QUEUE_CONNECTION=DATABASE тогда воркеры будут работать параллельно, а
данные для каждой сущности загружаться асинхронно.
- в env.example уже указаны хост и ключ для целевого апи

### База данных
в env.example уже указаны валидные подключения
- DB_CONNECTION=mysql
- DB_HOST=db12.ipipe.ru
- DB_PORT=3306
- DB_DATABASE=zetsubo3_db0
- DB_USERNAME=zetsubo3_db0
- DB_PASSWORD=0gqJORw1dyTx

Пустые целевые таблицы уже созданы:
- incomes
- orders
- sales
- stocks

### Использование
#### Команды для загрузки 
##### Для всех сущностей 
- php artisan api:data-fetch
##### Для отдельных сущностей:
- php artisan api:data-fetch --entity=incomes
- php artisan api:data-fetch --entity=orders
- php artisan api:data-fetch --entity=sales
- php artisan api:data-fetch --entity=stocks
#### Команда для просмотра прогресса загрузки 
- (в другом терминале) php artisan log:watch
### Обход ошибки 429
- При получение 429 ошибки от целевого апи нужно увеличить время задержки между запросами в конфиге api_target.php в ключе request_delay_ms.
- Рэйт лимиттер на целевом апи не настроен уникально для каждого ендпоинта, и готов обрабатывать 60 запросов в минуту с общей квотой на все 4 ендпоинта.
- При массовой загрузки нужно увеличить делэй с учетом каждой загружаемой сущности

