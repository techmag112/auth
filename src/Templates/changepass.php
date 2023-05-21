<?php 
    use function Tamtamchik\SimpleFlash\flash;
    use \Tm\Auth\Core\Token;
    if(isset($message)) {
        flash()->message($message, $type);
    }
    $this->layout('template', ['title' => 'Смена пароля']); 
?>
 
 <div class="container">
 <nav>
        <ul class="menu-main">
        <li><a href="/">Приветствуем, <?= $username ?></a></li>
        <li><a href="/update">Обновить профиль</a></li>
        <li><a href="/changepass" class="current">Изменить пароль</a></li>
        <li><a href="/logout">Выход</a></li>
        </ul>
    </nav>
    <hr>

<form action="" method="post" class="col-12">

    <?php echo flash()->displayBootstrap(); ?>
    
    <div class="field">
            <label for="currentpass" class="form-label">Введите текущий пароль</label>
            <input type="password" name="current_pass" class="form-control">
        </div>

        <div class="field">
            <label for="" class="form-label">Новый пароль</label>
            <input type="password" name="new_pass" class="form-control">
        </div>
        
        <div class="field">
            <label for="" class="form-label">Новый пароль еще раз</label>
            <input type="password" name="new_pass_again" class="form-control">
        </div>
        <br>
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"> 
        <div class="field">
            <button type="submit" class="w-100 btn btn-primary btn-lg">Обновить</button>
        </div>
        
</form>
</div>