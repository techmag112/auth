# Авторизация - стандартная и через VK на PHP

Сделано в качестве практического задания на курсе **PHP**

### Примененные технологии
* PHP, ООП, Bootstrap 5.3

### Реализованный функционал

* Идеалогия ООП (MVC).
* Использование готовых библиотек - monolog/monolog (логирование), tamtamchik/simple-flash (флеш-ссобщения об ошибках), league/plates (темплейты страниц)
* Вынос всех настроек в файл bootstrap.php
* Верстка на базе стилей Bootstrap 5.
* Авторизация.
* Хранение паролей в виде хешей. 
* Установка требований безопасности к новым паролям (длина, сложность).
* Возможность правки профиля пользователя и смены пароля.    
* Вывод флеш сообщений об результате операции.
* Верификация ввода данных кодированным токеном.
* Запоминание регистрации в cookie.
* Вывод главной страницы в зависимостью от роли (определяется вариантом регистрации).

## Как запустить проект:
1. Создать в MySQL базу project.
2. Создать таблицы:
CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `user_sessions` (
  `user_id` int NOT NULL,
  `hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;
3. Поместить код в папку локального сервера localhost
4. Запустить файл index.php



