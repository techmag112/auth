<?php 
    use function Tamtamchik\SimpleFlash\flash;
    if(isset($message)) {
        flash()->message($message, $type);
    }
    $this->layout('template', ['title' => 'Главная']); 
?>

<div class="container">
<nav>
        <ul class="menu-main">
            <?php if(isset($username)): ?>
                <li><a href="/">Приветствуем, <?= $username ?></a></li>
            <?php if((isset($username)) && ($role === 0)): ?>
                <li><a href="/update">Обновить профиль</a></li>
                <li><a href="/changepass">Изменить пароль</a></li>
            <?php endif; ?>
                <li><a href="/logout">Выход</a></li>
            <?php else: ?>
                <li><a href="/">Приветствуем, незнакомец</a></li>
                <li><a href="/login">Войти</a></li>
                <li><a href="/register">Регистрация</a></li>
            <?php endif; ?>
        </ul>
</nav>
    <hr>

    <?php echo flash()->displayBootstrap(); ?>

    <?php if(isset($username)): ?>
    <div class="text-center">
        <p>Текст (от лат. textus — ткань; сплетение, сочетание) — зафиксированная на каком-либо материальном носителе человеческая мысль; в общем плане связная и полная последовательность символов.
           Существуют две основные трактовки понятия «текст»: имманентная (расширенная, философски нагруженная) и репрезентативная (более частная). Имманентный подход подразумевает отношение к тексту как к автономной реальности, нацеленность на выявление его внутренней структуры. Репрезентативный — рассмотрение текста как особой формы представления информации о внешней тексту действительности.</p>
    </div>
    <?php endif; ?>
    <?php if((isset($username)) && ($role === 1)): ?>
        <div class="container-fluid" style="margin-left:400px">
            <img src="public/uploads/img/1676378718193599830.jpg"></img>
        </div>
    <?php endif; ?>

</div>

    