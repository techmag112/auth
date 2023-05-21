<?php 

namespace Tm\Auth\Core;

class VK {

    private $token;

    public function getToken($code) {

            $params = array(
                'client_id'     => Config::get('vk.client_id'),
                'client_secret' => Config::get('vk.client_secret'),
                'redirect_uri'  => Config::get('vk.redirect_uri'),
                'code' => $code,
            );
        
            $this->token = json_decode(file_get_contents('http://oauth.vk.com/access_token?' . urldecode(http_build_query( $params))), true);
            if($this->token) {
                return $_SESSION['token'] = $this->token;
            } else {
                return false;
            }
 
    }    

    public function getInfo($token) {

        if($token) {
            $params = [
                'uids' => $token['user_id'],
                'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
                'access_token' => $token['access_token'],
                'v' => '5.131'];

                if($userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true)) {
                    return $userInfo;
                }
        }
        return false;
    }

}