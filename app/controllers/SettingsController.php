<?php

require_once "app/models/SettingsModel.php";

class SettingsController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo=$pdo;
    }

    public function index()
    {
        if(!isset($_SESSION["id"])){
            header("Location: login.php");
            exit;
        }

        $userId=$_SESSION["id"];

        $settings=new SettingsModel($this->pdo);

        $message="";
        $error="";

        $user=$settings->getUser($userId);

        if(isset($_POST["change_password"])){

            $old=$_POST["old_password"];
            $new=$_POST["new_password"];
            $again=$_POST["again_password"];

            if(!password_verify($old,$user["password"])){

                $error="Mevcut şifre yanlış.";

            }elseif($new!=$again){

                $error="Yeni şifreler uyuşmuyor.";

            }else{

                $hash=password_hash($new,PASSWORD_DEFAULT);

                $settings->changePassword($userId,$hash);

                $message="Şifreniz başarıyla değiştirildi.";

                $user=$settings->getUser($userId);

            }

        }

        if(isset($_POST["delete_account"])){

            $password=$_POST["delete_password"];

            if(password_verify($password,$user["password"])){

                $settings->deleteAccount($userId);

                session_destroy();

                header("Location: login.php");
                exit;

            }else{

                $error="Hesap silme şifresi yanlış.";

            }

        }

        require "app/views/settings/index.php";
    }
}