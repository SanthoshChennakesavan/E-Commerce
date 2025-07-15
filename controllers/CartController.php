<?php 
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Cart;
use app\modules\admin\models\Products;
use app\models\Wishlist;
use app\modules\admin\models\Category;

class CartController extends Controller
{
    public function actionCartindex()
    {
        $userId = Yii::$app->user->id;

        if (!$userId) {
            return $this->redirect(['site/login']);
        }

        $cartItems = Cart::find()
            ->alias('c')
            ->joinWith(['product p', 'product.category'])
            ->where(['c.user_id' => $userId])
            ->andWhere(['p.status' => 1])
            ->all();

        $wishlistProductIds = Wishlist::find()
            ->select('product_id')
            ->where(['user_id' => $userId])
            ->column();

        return $this->render('cartindex', [
            'cartItems' => $cartItems,
            'wishlistProductIds' => $wishlistProductIds,
        ]);
    }


    public function actionRemove()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $cartId = Yii::$app->request->post('id');

        $cartItem = Cart::findOne($cartId);

        if ($cartItem && $cartItem->user_id == Yii::$app->user->id) {
            $cartItem->removeItem();
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Item not found or unauthorized.'];
    }

    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            if (Yii::$app->user->isGuest) {
                return ['success' => false, 'message' => 'Login required'];
            }

            $productId = Yii::$app->request->post('id');
            $quantity = Yii::$app->request->post('quantity', 1);
            $userId = Yii::$app->user->id;

            if (!$productId || !$userId) {
                return ['success' => false, 'message' => 'Missing data'];
            }

            $product = Products::findOne($productId);
            if (!$product) {
                return ['success' => false, 'message' => 'Invalid product'];
            }

            $cart = new Cart();
            $cart->user_id = $userId;
            $cart->product_id = $productId;

            if ($cart->addToCart((int)$quantity)) {
                return [
                    'success' => true,
                    'message' => 'Item added/updated',
                    'cartCount' => Cart::getUserCartCount($userId)
                ];
            }
            return ['success' => false, 'message' => 'Error saving cart item'];

        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function actionUpdateQuantity()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $cartId = Yii::$app->request->post('id');
        $quantity = (int) Yii::$app->request->post('quantity');

        $cart = Cart::findOne($cartId);

        if ($cart && $cart->user_id == Yii::$app->user->id) {
            if ($cart->updateCartQuantity($quantity)) {
                return [
                    'success' => true,
                    'newTotal' => $cart->quantity * $cart->product->productprice
                ];
            }
        }

        return ['success' => false, 'message' => 'Invalid cart item or quantity'];
    }
}
