<?php
class db
{
    private static $_instance = null;

    private $db; // Ресурс работы с БД

    /*
     * Получаем объект для работы с БД
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new db();
        }
        return self::$_instance;
    }
    /*
     * Запрещаем копировать объект
     */
    private function __construct() {
        $this->db = new PDO('mysql:host=' .Config::get('db_host') . ';dbname='.Config::get('db_base'), Config::get('db_user'), Config::get('db_password'));
        $this->db->exec('SET NAMES UTF8');
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    private function __sleep() {}
    private function __wakeup() {}
    private function __clone() {}

    public function Select($query,$arr){
        $q = $this->db->prepare($query);
        $q->execute($arr);

        if($q->errorCode() != PDO::ERR_NONE){
            $info = $q->errorInfo();
            die($info[2]);
        }
        return $q->fetchAll();
    }


    public function Insert($table , $object){
        $columns = array();

        foreach($object as $key => $value){

            $columns[] = $key;
            $masks[] = ":$key";

            if($value === null){
                $object[$key] = 'NULL';
            }
        }

        $columns_s = implode(',', $columns);
        $masks_s = implode(',', $masks);

        $query = "INSERT INTO $table ($columns_s) VALUES ($masks_s)";

        $q = $this->db->prepare($query);
        $q->execute($object);

        if($q->errorCode() != PDO::ERR_NONE){
            $info = $q->errorInfo();
            die($info[2]);
        }

        return $this->db->lastInsertId();
    }

    public function Update($table,$object,$where){
        $sets = array();

        foreach($object as $key => $value){

            $sets[] = "$key=:$key";

            if($value === NULL){
                $object[$key]='NULL';
            }
        }

        $sets_s = implode(',',$sets);
        $query = "UPDATE $table SET $sets_s WHERE $where";

        $q = $this->db->prepare($query);
        $q->execute($object);

        if($q->errorCode() != PDO::ERR_NONE){
            $info = $q->errorInfo();
            die($info[2]);
        }

        return $q->rowCount();
    }


    public function Delete($table, $where){
        $query = "DELETE FROM $table WHERE $where";
        $q = $this->db->prepare($query);
        $q->execute();

        if($q->errorCode() != PDO::ERR_NONE){
            $info = $q->errorInfo();
            die($info[2]);
        }

        return $q->rowCount();
    }
}
?>
