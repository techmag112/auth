
<?php 
    use function Tamtamchik\SimpleFlash\flash;
    use \Tm\Auth\Core\Input;
    use \Tm\Auth\Core\Token;
    if(isset($message)) {
        flash()->message($message, $type);
    }
    $vk_url = 'http://oauth.vk.com/authorize?' . http_build_query( $params );
    $this->layout('template', ['title' => 'Авторизация']); 
?>

<div class="container">
<form action="" method="post" class="card p-2">
        
        <?php echo flash()->displayBootstrap(); ?> 

        <div class="field" class="col-12">
            <label for="email" class="form-label">Email</label>
            <input type="text" name="email" class="form-control" value="<?php echo Input::get('email')?>">
        </div>

        <div class="field" class="col-12">
            <label for="" class="form-label">Password</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="field">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">Запомнить</label>
        </div>

        <br>      
        <input type="hidden" name="token" class="form-control" value="<?php echo Token::generate(); ?>"> 
        <div class="field">
            <button type="submit" class="w-100 btn btn-primary btn-lg">Авторизоваться</button>
        </div>
        <br>    
        <div class="field">
            <button type="button" class="w-100 btn btn-primary btn-lg" onclick="document.location='<?=$vk_url?>'">Авторизоваться через ВКонтакте</button>
        </div>
</form>
</div>