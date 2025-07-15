<?php

namespace app\modules\admin\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Products;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $categoryname
 * @property string $categorydes
 * @property int $status
 * @property string $category_seourl
 *
 * @property Products[] $products
 */
class Category extends ActiveRecord
{
    public static function tableName()
    {
        return 'category';
    }

    public function rules()
    {
        return [
            [['categoryname', 'categorydes', 'status'], 'required'],
            [['status'], 'integer'],
            [['categoryname'], 'string', 'max' => 100],
            [['categorydes'], 'string', 'max' => 300],
            [['category_seourl'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoryname' => 'Category Name',
            'categorydes' => 'Category Description',
            'status' => 'Status',
            'category_seourl' => 'Category SeoURL',
        ];
    }

    public function getProducts()
    {
        return $this->hasMany(Products::class, ['categoryid' => 'id']);
    }

    public static function getFilteredCategoryList($categoryname = null)
    {
        $query = self::find()->where(['status' => 1]);

        if (!empty($categoryname)) {
            $query->andFilterWhere(['like', 'categoryname', $categoryname]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);
    }

    public function softDeleteWithCheck()
    {
        $productCount = Products::find()
            ->where(['categoryid' => $this->id, 'status' => 1])
            ->count();

        if ($productCount > 0) {
            Yii::$app->session->setFlash('error', 'Cannot delete category. It is assigned to products.');
            return false;
        }

        $this->status = 0;
        $this->categoryname .= ' [Deleted]';

        if ($this->save(false)) {
            Yii::$app->session->setFlash('success', 'Category deleted successfully.');
            return true;
        }

        Yii::$app->session->setFlash('error', 'Failed to delete category.');
        return false;
    }
    
    public function getCategoryCount()
    {
        return self::find()->where(['status' => 1])->count();
    }

    public function getAllCategories()
    {
        return self::find()->where(['status' => 1])->all();
    }

    public function getPieChartData()
    {
        $categories = $this->getAllCategories();
        $labels = [];
        $data = [];

        foreach ($categories as $category) {
            $labels[] = $category->categoryname;
            $data[] = Products::find()->where(['categoryid' => $category->id, 'status' => 1])->count();
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
