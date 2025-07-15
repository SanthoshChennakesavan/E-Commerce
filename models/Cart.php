<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\User;
use app\modules\admin\models\Products;

class Cart extends ActiveRecord
{
    public static function tableName()
    {
        return 'cart';
    }

    public function rules()
    {
        return [
            [['user_id', 'product_id', 'quantity'], 'required'],
            [['user_id', 'product_id', 'quantity'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function addToCart($quantity = 1)
    {
        $existing = self::findOne(['user_id' => $this->user_id, 'product_id' => $this->product_id]);
        if ($existing) {
            $existing->quantity += $quantity;
            return $existing->save(false);
        }
        $this->quantity = $quantity;
        return $this->save(false);
    }

    public function updateCartQuantity($quantity)
    {
        if ($quantity > 0) {
            $this->quantity = $quantity;
            return $this->save(false);
        }
        return false;
    }

    public function removeItem()
    {
        return $this->delete();
    }

    public static function getUserCartItems($userId)
    {
        return self::find()->where(['user_id' => $userId])->with('product.category')->all();
    }

    public static function getUserCartCount($userId)
    {
        return self::find()
            ->alias('c')
            ->joinWith(['product p'])
            ->where(['c.user_id' => $userId])
            ->andWhere(['p.status' => 1]) 
            ->count();
    }

}
