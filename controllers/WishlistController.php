<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Wishlist;
use app\modules\admin\models\Category;
use app\modules\admin\models\Products;

class WishlistController extends Controller
{
    public function actionIndex()
    {
       $userId = Yii::$app->user->id;

        if (!$userId) {
            return $this->redirect(['site/login']);
        }

        $wishlistProducts = Wishlist::find()
            ->where(['user_id' => $userId])
            ->with('product')
            ->all();

        $categories = Category::find()->where(['status' => 1])->all();

        $wishlistProductIds = array_map(fn($item) => $item->product_id, $wishlistProducts);

        return $this->render('index', [
            'wishlistProducts' => $wishlistProducts,
            'categories' => $categories,
            'selectedCategories' => [],
            'wishlistProductIds' => $wishlistProductIds,
        ]);
    }

    public function actionToggle()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        Yii::error("WISHLIST TOGGLE TRIGGERED");

        $userId = Yii::$app->user->id;
        $productId = Yii::$app->request->post('id');

        if (!$userId) {
            return ['success' => false, 'message' => 'Login required'];
        }

        if (!$productId) {
            return ['success' => false, 'message' => 'Invalid product ID'];
        }

        $wishlist = Wishlist::findOne(['user_id' => $userId, 'product_id' => $productId]);

        if ($wishlist) {
            $wishlist->delete();
            return ['success' => true, 'status' => 'removed'];
        } else {
            $wishlist = new Wishlist();
            $wishlist->user_id = $userId;
            $wishlist->product_id = $productId;

            if ($wishlist->save()) {
                return ['success' => true, 'status' => 'added'];
            } else {
                return ['success' => false, 'message' => 'Failed to save to wishlist'];
            }
        }
    }
}
