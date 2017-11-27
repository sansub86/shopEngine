<?php

class Order extends Model {
    protected static $table = 'orders';

    protected static function setProperties()
    {
        self::$properties['phone'] = [
            'type' => 'varchar',
            'size' => 512
        ];

        self::$properties['address'] = [
            'type' => 'varchar',
            'size' => 512
        ];

        self::$properties['email'] = [
            'type' => 'float'
        ];
    }
    public static function getOrder($userID){
        return db::getInstance()->Select('SELECT id FROM orders WHERE user_id=:user_id',['user_id'=>$userID['user_id']]);
    }
    public static function getUserOrder($userId, $orderId){
        return db::getInstance()->Select('SELECT id_good, price, quantity, date_creation FROM basket INNER JOIN orders ON basket.id_order = orders.id WHERE id_user=:user_id, id_oreder=:order_id',['user_id' => $userId, 'oreder_id'=>$orderId]);
    }
    public static function saveOrder($data){
        return db::getInstance()->Select('INSERT INTO orders (date_creation, phone, user_id, address, status) VALUES (:date_creation, :phone, :user_id, :address, 1)', ['date_creation'=>date('Y-m-d H:i:s',time()), 'phone' => $data['phone'], 'user_id' =>$data['user_id'],'address'=>$data['address']]);
    }
}

