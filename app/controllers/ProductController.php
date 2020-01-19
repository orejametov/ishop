<?php


namespace app\controllers;


use app\models\Product;
use RedBeanPHP\R;

class ProductController extends AppController
{
    public function viewAction(){
        $alias = $this->route['alias'];
        $product = R::findOne('product', "alias = ? AND status ='1'", [$alias]);
        if(!$product){
            throw new \Exception('Страница не найдена', 404);
        }

//        хлебные крошки

//        cвязанные товары
        $related = R::getAll("SELECT * FROM related_product JOIN product ON 
                                product.id = related_product.related_id WHERE related_product.product_id = ?", [$product->id]);
//        запись в куки запрошенного товараx
        $p_model = new Product();
        $p_model->setRecentlyViewed($product->id);


//        просмотренные товары
        $r_viewed = $p_model->getRecentlyViewed();
        $recentlyViwed = null;
        if($r_viewed){
            $recentlyViwed = R::find('product', 'id IN (' .R::genSlots($r_viewed).') LIMIT 3', $r_viewed);
        }
//        галерея
        $gallery = R::findAll('gallery', 'product_id = ?', [$product->id]);
//        модификации товара

        $this->setMeta($product->title, $product->description, $product->keywords);
        $this->set(compact('product', 'related', 'gallery' , 'recentlyViwed'));
    }
}