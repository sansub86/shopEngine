<?php

class OrderController extends Controller
{
    public $view='order';
    function __construct(){
        parent::__construct();
        $this->title=' | Оформление покупки';
    }
    public function index($data)
    {
        if(!isset($_SESSION['user_id'])){
            return ['message'=>'Для оформления заказа пожалуйста авторизуйтесь'];
        }else{
            return['user'=>$_SESSION['user_name']];
            //выберем все заказы авторизованного пользователя
        }
    }
    public function add($data){
        if(isset($_SESSION['basket'])){
            //Если карзина существует
            if(isset($_SESSION['user_id'])){

                //Если пользователь авторизован
                $order = ['user_id' => $_SESSION['user_id'], 'phone' => $_GET['phone'], 'address' => $_GET['address']];
                Order::saveOrder($order);
                $orders = Order::getOrder($order);
                $basket =[$_SESSION['basket'], 'is_in_order'=>1, 'order_id'=>$orders[count($orders)-1]['id']];

                Basket::saveBasket($basket);
                return ['user'=> isset($_SESSION['user_name'])?$_SESSION['user_name']:''];
            } else{
                return ['message'=>'Для оформления заказа пожалуйста авторизуйтесь'];
            }
        }
    }
}