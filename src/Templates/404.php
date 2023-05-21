<?php 
    $this->layout('template', ['title' => 'Главная']); 
?>

<nav>
        <ul class="menu-main">
            <?php if(isset($username)): ?>
                <li><a href="/">Приветствуем, <?= $username ?></a></li>
                <li><a href="/update">Обновить профиль</a></li>
                <li><a href="/changepass">Изменить пароль</a></li>
                <li><a href="/logout">Выход</a></li>
            <?php else: ?>
                <li><a href="/">Приветствуем, незнакомец</a></li>
                <li><a href="/login">Войти</a></li>
                <li><a href="/register">Регистрация</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <hr>

<div class="container">
    <img src="public/css/404.jpg" class="img-fluid"> 
</div>