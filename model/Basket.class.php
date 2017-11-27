<?php
class Basket extends Model{
    protected static $table = 'basket';

    protected static function setProperties()
    {
        self::$properties['price'] = [
            'type' => 'double',
        ];
        self::$properties['user_id'] = [
            'type' => 'int',
        ];
        self::$properties['good_id'] = [
            'type' => 'int',
        ];
        self::$properties['order_id'] = [
            'type' => 'int',
        ];
    }
    public static function getBasket($basket){
        $basketList = [];
        foreach($basket as $key=>$value) {
            $goodName = db::getInstance()->Select('SELECT name, price FROM goods WHERE id=:id', ['id' => $value['good_id']]);
            $basketList[$key] = [$goodName[0]['name'], $value['quantity'],$goodName[0]['price']];

        }

        return $basketList;
    }
    public static function saveBasket($data){

        foreach ($data[0] as $key=> $value){
            $res = db::getInstance()->Select('INSERT INTO basket (id_user, id_good, price, is_in_order, id_order, quantity) VALUES (:id_user, :id_good, :price, :is_in_order, :id_order, :quantity)', ['id_user'=>$value['user_id'], 'id_good' => $value['good_id'], 'price'=>Good::getGood($value['good_id'])[0]['price'], 'is_in_order' =>$data['is_in_order'],'id_order'=>$data['order_id'], 'quantity'=>$value['quantity']]);
        }
        return $res;
    }
}
