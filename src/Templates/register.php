<?php 
    use function Tamtamchik\SimpleFlash\flash;
    use \Tm\Auth\Core\Input;
    use \Tm\Auth\Core\Token;
    if(isset($message)) {
        flash()->message($message, $type);
    }
    $this->layout('template', ['title' => 'Регистрация']); 
?>

<div class="container">
<form action="" method="post" class="card p-2">

        <?php echo flash()->displayBootstrap(); ?>
        
        <div class="col-12">
            <label for="username" class="form-label">Никнейм</label>
            <input type="text" name="username" class="form-control" value="<?php echo Input::get('username')?>">
        </div>

        <div  class="col-12">
            <label for="email" class="form-label">Email</label>
            <input type="text" name="email" class="form-control" value="<?php echo Input::get('email')?>">
        </div>

        <div  class="col-12">
            <label for="" class="form-label">Пароль</label>
            <input type="password" name="password" class="form-control">
        </div>
        
        <div  class="col-12">
            <label for="" class="form-label">Пароль еще раз</label>
            <input type="password" name="password_again" class="form-control">
        </div>
        <br>
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"> 
        <div class="field">
            <button type="submit" class="w-100 btn btn-primary btn-lg">Сохранить</button>
        </div>
</form>
</div>
