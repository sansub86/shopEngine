<?php
class CategoriesController extends Controller
{

    public $view = 'categories';
    private function getAllGoodsInCategory($categories, $goods){ //показываем все товары.
            if(!empty($categories)) {
                foreach ($categories as $value) {
                    $goods = array_merge($goods, Good::getGoods($value['id']));
                    $goods = CategoriesController::getAllGoodsInCategory(Category::getCategories($value['id']), $goods);
                }
            }
            return $goods;
        }
    public function index($data)
    {
        $categories = Category::getCategories(isset($data['id']) ? $data['id'] : 0);
        $goods = Good::getGoods(isset($data['id']) ? $data['id'] : 0);
        $goods = CategoriesController::getAllGoodsInCategory($categories, $goods);
        if (empty($goods)) {
            return ['subcategories' => $categories, 'message' => 'Нет товаров в данной категории.'];
        }
        return ['subcategories' => $categories, 'goods' => $goods, 'user'=> isset($_SESSION['user_name'])?$_SESSION['user_name']:''];
    }
}
?>