<?php 
    use function Tamtamchik\SimpleFlash\flash;
    use \Tm\Auth\Core\Token;
    if(isset($message)) {
        flash()->message($message, $type);
    }
    $this->layout('template', ['title' => 'Обновление профиля']); 
?>
<div class="container">
 <nav>
        <ul class="menu-main">
        <li><a href="/">Приветствуем, <?= $username ?></a></li>
        <li><a href="/update" class="current">Обновить профиль</a></li>
        <li><a href="/changepass">Изменить пароль</a></li>
        <li><a href="/logout">Выход</a></li>
        </ul>
    </nav>
    <hr>

<form action="" method="post" class="card p-2">

        <?php echo flash()->displayBootstrap(); ?>

        <div class="field" class="col-12">
            <label for="username" class="form-label">Имя пользователя</label>
            <input type="text" name="username" class="form-control" value="<?php echo $username ?>">
        </div>
        <br>
        <input type="hidden" name="token" class="form-control" value="<?php echo Token::generate(); ?>"> 
        <div class="field">
            <button type="submit" class="w-100 btn btn-primary btn-lg">Обновить</button>
        </div>
</form>
</div>
