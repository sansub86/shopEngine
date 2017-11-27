<?php

class Good extends Model {
    protected static $table = 'goods';

    protected static function setProperties()
    {
        self::$properties['name'] = [
            'type' => 'varchar',
            'size' => 512
        ];

        self::$properties['price'] = [
            'type' => 'float'
        ];

        self::$properties['description'] = [
            'type' => 'text'
        ];

        self::$properties['category'] = [
            'type' => 'int'
        ];
    }

    public static function getGoods($categoryId)
    {
        return db::getInstance()->Select(
            'SELECT id, id_category, `name`, price FROM goods WHERE id_category = :category AND status=:status',
            ['status' => Status::Active, 'category' => $categoryId]);
    }
    public static function getGood($GoodId)
    {
        return db::getInstance()->Select(
            'SELECT id, id_category, `name`, price, description FROM goods WHERE id = :good AND status=:status',
            ['status' => Status::Active, 'good' => $GoodId]);
    }
    public static function getGoodByName($goodName){
        return db::getInstance()->Select(
            'SELECT id FROM goods WHERE `name` = :name AND status=:status',
            ['status' => Status::Active, 'name' => $goodName]);
    }
}