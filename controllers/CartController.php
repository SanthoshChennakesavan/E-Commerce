<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Cart;
use app\modules\admin\models\Category;
use app\modules\admin\models\Products;

class CartController extends Controller
{
   public function actionCartindex()
{
    $userId = Yii::$app->user->id;

    if (!$userId) {
        return $this->redirect(['site/login']);
    }

    $cartItems = \app\models\Cart::find()
        ->where(['user_id' => $userId])
        ->with('product')
        ->all();

    return $this->render('cartindex', [
        'cartItems' => $cartItems
    ]);
}

public function actionRemove()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $cartId = Yii::$app->request->post('id');

    $cartItem = \app\models\Cart::findOne($cartId);

    if ($cartItem && $cartItem->user_id == Yii::$app->user->id) {
        $cartItem->delete();
        return ['success' => true];
    }

    return ['success' => false, 'message' => 'Item not found or unauthorized.'];
}

public function actionAdd()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    try {
        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Login required'];
        }

        $productId = Yii::$app->request->post('id');
        $userId = Yii::$app->user->id;

        if (!$productId || !$userId) {
            return ['success' => false, 'message' => 'Missing data', 'productId' => $productId, 'userId' => $userId];
        }

        $product = Products::findOne($productId);
        if (!$product) {
            return ['success' => false, 'message' => 'Invalid product ID: ' . $productId];
        }

        $existingCartItem = \app\models\Cart::findOne([
            'user_id' => $userId,
            'product_id' => $productId
        ]);

        if ($existingCartItem) {
            $existingCartItem->quantity += 1;
            if ($existingCartItem->save()) {
                return ['success' => true, 'message' => 'Quantity increased'];
            } else {
                return ['success' => false, 'message' => 'Failed to update cart item', 'errors' => $existingCartItem->errors];
            }
        }

        $cart = new \app\models\Cart();
        $cart->user_id = $userId;
        $cart->product_id = $productId;
        $cart->quantity = 1;

        if ($cart->save()) {
            return ['success' => true, 'message' => 'Added to cart'];
        }

        return ['success' => false, 'message' => 'Failed to save new cart item', 'errors' => $cart->errors];

    } catch (\Throwable $e) {
        return ['success' => false, 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString()];
    }
}


}
