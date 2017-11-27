<?php
class AdminController extends Controller
{
    
    protected $controls = [
        'pages' => 'Страницы',
        'orders' => 'Заказы',
        'categories' => 'Категории',
        'goods' => 'Товары'
    ];

    public $title = 'admin';
    
    public function index($data)
    {
        if($_SESSION['user_login']=='admin'){
            return ['user' => $_SESSION['user_name'] ,'controls' => $this->controls];
        }


    }
    public function control($data)
    {
        // Сохранение
        $actionId = $this->getActionId($data);
        if ($actionId['action'] === 'save') {
            $fields = [];
            foreach ($_POST as $key => $value) {
                $field = explode('_', $key, 2);

                if ($field[0] == $actionId['id']) {
                    $fields[$field[1]] = $value;


                }
            }
        }

        if ($actionId['action'] === 'create') {
            $fields = [];
            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 4) == 'new_') {
                    $fields[str_replace('new_', '', $key)] = $value;
                }
            }
        }

        switch($actionId['action']) {
            case 'create':
                $query = 'INSERT INTO ' . $data['id'] . ' ';
                $keys = [];
                $values = [];
                foreach ($fields as $key => $value) {
                    $keys[] = $key;
                    $values[] = '"' . $value . '"';
                }

                $query .= ' (' . implode(',', $keys) . ') VALUES ( ' . implode(',', $values) . ')';
                db::getInstance()->Select($query,[]);
                break;
            case 'save':
                $query = 'UPDATE ' . $data['id'] . ' SET ';
                foreach ($fields as $field => $value) {
                    $query .= $field . ' = "' . $value . '", ';
                }
                $query = substr($query, 0, -2) . ' WHERE id = :id';
                db::getInstance()->Select($query, ['id' => $actionId['id']]);
                break;
            case 'delete':
                db::getInstance()->Select('DELETE FROM ' . $data['id'] . ' WHERE id = :id', ['id' => $actionId['id']]);
                break;
        }
        $fields = db::getInstance()->Select('desc ' . $data['id'],[]);
        $_items = db::getInstance()->Select('select * from ' . $data['id'],[]);
        $items = [];
        /*
        foreach ($_items as $item) {
            var_dump($item);
            $items[] = new $this->controls[$data['id']]($item);

        }
        */
        return ['user'=> $_SESSION['user_name'],'name' => $this->controls[$data['id']],'fields' => $fields, 'items' => $_items];
    }

    protected function getActionId($data)
    {
        $id=0;
        $action='';
        //var_dump($_POST);
        foreach ($_POST as $key => $value) {
            if (strpos($key, '__save_') === 0) {
                $id = explode('__save_', $key)[1];
                $action = 'save';
                break;
            }
            if (strpos($key, '__delete_') === 0) {
                $id = explode('__delete_', $key)[1];
                $action = 'delete';
                break;
            }
            if (strpos($key, '__create') === 0) {
                $action = 'create';
                $id = 0;
            }
        }
        return ['id' => $id, 'action' => $action];
    }
}