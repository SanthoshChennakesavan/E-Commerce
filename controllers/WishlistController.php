<?php 
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Wishlist;
use app\modules\admin\models\Category;

class WishlistController extends Controller
{
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;

        if (!$userId) {
            return $this->redirect(['site/login']);
        }

        $wishlistProducts = Wishlist::getUserWishlist($userId);
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

        $userId = Yii::$app->user->id;
        $productId = Yii::$app->request->post('id');

        if (!$userId) {
            return ['success' => false, 'message' => 'Login required'];
        }

        if (!$productId) {
            return ['success' => false, 'message' => 'Invalid product ID'];
        }

        return Wishlist::toggleWishlist($userId, $productId);
    }
}
