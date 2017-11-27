<?php
class UserController extends Controller
{
    public $view = 'user';

    public function index($data){
        if (!isset($_SESSION['user_name'])) {
            header("Location: /user/login/");
            exit;
        }else{
            $user = $_SESSION['user_name'];
            //отобразим заказы текущего пользователя.
            $userOrders = User::getUserOrders($_SESSION['user_id']);
            return ['user' => $user, 'user_orders'=>$userOrders];
        }

    }
    public function registration()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ($_POST['newname']) $name =$_POST['newname'];
            if ($_POST['newuser']) $login = $_POST['newuser'];
            if ($_POST['newpass']) $password = md5($_POST['newpass']);

            if(!User::isUser($login)){
                $user = User::createUser($login,$name,$password);
                //var_dump($user);
                return ['user' => $user];
            }
            return  ['message' => 'Пользователь с таким логином уже существует!'];
        }

    }
    public function login(){
        var_dump($_SESSION);
        if(isset($_SESSION['user_name'])){
            $user = $_SESSION['user_name'];
            return ['user' => $user];
        }
        if($_SERVER['REQUEST_METHOD']=='POST'){
            if($_POST['login']) $login = $_POST['login'];
            if($_POST['password']) $password = md5($_POST['password']);

            $user= User::getUser($login, $password);
            if($user!=null){
                $_SESSION['user_name']=$user[0]['user_name'];
                $_SESSION['user_login']=$user[0]['user_login'];
                $_SESSION['user_id']=$user[0]['id'];
                header("Location: /user/");
                return ['user'=>$user[0]['user_name']];
            }
            return ['message'=> 'Неверное имя пользователя или пароль'];
        }
    }
    public function logout()
    {
        if (isset($_SESSION['user_name'])) {
            var_dump($_SESSION);
            unset($_SESSION['user_name']);
            return ['message' => 'Вы вышли из аккаунта'];

        }
        return  ['message' => 'Не найдено текущего пользователя'];
    }
}