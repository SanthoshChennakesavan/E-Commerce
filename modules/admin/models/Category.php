<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $categoryname
 * @property string $categorydes
 * @property int $status
 *
 * @property Products[] $products
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoryname', 'categorydes', 'status'], 'required'],
            [['status'], 'integer'],
            [['categoryname'], 'string', 'max' => 100],
            [['categorydes'], 'string', 'max' => 300],
            [['category_seourl'], 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoryname' => 'Category Name',
            'categorydes' => 'Category Description',
            'status' => 'Status',
            'category_seourl' => 'Category SeoURL'
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::class, ['categoryid' => 'id']);
    }

}
