<?php

namespace app\models;

use Yii;
use app\modules\admin\models\Products;

class Wishlist extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'wishlist';
    }

    public function rules()
    {
        return [
            [['user_id', 'product_id'], 'required'],
            [['user_id', 'product_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
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

    // Custom methods to move DB logic here
    public static function getUserWishlist($userId)
    {
        return self::find()
            ->where(['user_id' => $userId])
            ->with('product')
            ->all();
    }

    public static function toggleWishlist($userId, $productId)
    {
        $wishlist = self::findOne(['user_id' => $userId, 'product_id' => $productId]);

        if ($wishlist) {
            $wishlist->delete();
            return ['success' => true, 'status' => 'removed'];
        } else {
            $wishlist = new self();
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
