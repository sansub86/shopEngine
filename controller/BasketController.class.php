<?php
class BasketController extends Controller{

    public $view = 'basket';

    function __construct(){
        parent::__construct();
        $this->title=' | Корзина';
    }
    public function index($data){
        if(isset($_SESSION['basket'])){
            $basket=Basket::getBasket($_SESSION['basket']);
            return['basket'=>$basket, 'user'=> isset($_SESSION['user_name'])?$_SESSION['user_name']:''];
        }
        return ['message'=>'Корзина пуста', 'user'=> isset($_SESSION['user_name'])?$_SESSION['user_name']:''];
    }
    public function add($data)
    {
        if(isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        else {
            $user_id = 'noName';
        }
        $basket = ['user_id' => $user_id, 'good_id' => $_GET['good_id'], 'quantity' => $_GET['quantity']];
        $_SESSION['basket'][] = $basket;
        return [ 'message' => 'Товар добавлен в корзину', 'basket' => $basket];

    }
    public function clear($data)
    {
        unset($_SESSION['basket']);
        return [ 'message' => 'Корзина очищена'];
    }
    public function change($data){
        if(isset($_SESSION['basket'])){
            //Если карзина существует

                foreach ($_SESSION['basket'] as $key => $value){

                    if($value['good_id']==Good::getGoodByName($_POST['goodName'])[0]['id']){
                        $_SESSION['basket'][$key]['quantity']=$_POST['quantity'];
                    }
                }
            }
    }

}