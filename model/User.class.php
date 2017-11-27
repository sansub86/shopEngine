<?php

class User extends Model{
    protected static $table = 'user';

    protected static function setProperties()
    {
        self::$properties['user_name'] = [
            'type' => 'varchar',
            'size' => 100
        ];

        self::$properties['user_password'] = [
            'type' => 'varchar',
            'size' => 100
        ];

        self::$properties['user_login'] = [
            'type' => 'varchar',
            'size' => 100
        ];
    }
    public static function createUser($login,$user,$password)
    {
        $id = db::getInstance()->Insert("user",
            ['user_login' => $login, 'user_name' => $user, 'user_password' => $password]);
        if ($id) {
            return $user;
        }
    }
    public static function getUser($login, $password)
    {
        return db::getInstance()->Select(
            'SELECT id, user_name, user_login FROM user WHERE user_login = :login AND user_password = :password',
            ['login' => $login, 'password' => $password]);

    }
    public static function isUser($userName)
    {
        return db::getInstance()->Select('SELECT id_user, user_name FROM user WHERE user_login = :login',
            ['login' => $userName]);
    }
    public static function getUserOrders($userId){
        $res = db::getInstance()->Select('SELECT id, date_creation, address, status FROM orders WHERE user_id=:userId',['userId'=>$userId]);
        foreach ($res as $key => $value){
            $basketList[$key]=db::getInstance()->Select('SELECT id_good, price, quantity FROM basket WHERE id_order=:order_id',['order_id'=>$value['id']]);
            $total=0;
            foreach ($basketList[$key] as $keyItem => $item){
                $goodName = db::getInstance()->Select('SELECT `name` FROM goods WHERE id=:id',['id'=>$item['id_good']]);
                $basketList[$key][$keyItem] = array_merge($item, $goodName[0], ['sum'=>$item['price']*$item['quantity']]);
                $total +=$basketList[$key][$keyItem]['sum'];

            }
            $res[$key]= array_merge($res[$key],['goods'=> $basketList[$key]],['total'=>$total]);
        }
        return $res;
    }

}