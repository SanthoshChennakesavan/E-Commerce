<?php

namespace app\modules\admin\models;

use Yii;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class Products extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public static function tableName()
    {
        return 'products';
    }

    public function rules()
    {
        return [
            [['categoryid', 'productname', 'productdes', 'productprice', 'stock', 'status', 'min_quantity', 'max_quantity'], 'required'],
            [['productprice', 'stock'], 'number'],
            [['categoryid', 'status', 'min_quantity', 'max_quantity'], 'integer'],
            [['productname'], 'string', 'max' => 100],
            [['productname'], 'validateUniqueProductName'],
            [['productdes'], 'string', 'max' => 300],
            [['productimage'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg, gif', 'skipOnEmpty' => true],
        ];
    }

    public function validateUniqueProductName($attribute, $params)
    {
        $query = self::find()->where(['productname' => $this->$attribute]);

        if (!$this->isNewRecord) {
            $query->andWhere(['<>', 'id', $this->id]);
        }

        if ($query->exists()) {
            $this->addError($attribute, 'This product name already exists.');
        }
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoryid' => 'Category',
            'productname' => 'Product Name',
            'productdes' => 'Description',
            'productprice' => 'Price',
            'productimage' => 'Product Image',
            'stock' => 'Stock',
            'status' => 'Status',
            'min_quantity' => 'Minimum Quantity',
            'max_quantity' => 'Maximum Quantity',
            'imageFile' => 'Upload Image',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'categoryid']);
    }

    public function productSearch($selectedCategories = null)
    {
        $query = self::find()->where(['status' => 1]);

        if (!empty($selectedCategories)) {
            $query->andWhere(['categoryid' => $selectedCategories]);
        }

        return new ActiveDataProvider([
            'query' => $query->with('category'),
            'pagination' => ['pageSize' => 8],
        ]);
    }

    public static function getFilteredProductList($productname = null)
    {
        $query = self::find()->where(['status' => 1]);

        if (!empty($productname)) {
            $query->andFilterWhere(['like', 'productname', $productname]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);
    }

    public function handleUploadAndSave()
    {
        if ($this->validate()) {
            if ($this->imageFile) {
                $fileName = uniqid() . '.' . $this->imageFile->extension;
                $filePath = Yii::getAlias('@webroot/uploads/') . $fileName;

                if ($this->imageFile->saveAs($filePath)) {
                    $this->productimage = $fileName;
                } else {
                    return false;
                }
            }
            return $this->save(false);
        }

        return false;
    }

    // Soft delete the product
    public function softDelete()
    {
        $imagePath = Yii::getAlias('@webroot/uploads/') . $this->productimage;
        if (file_exists($imagePath)) {
            @unlink($imagePath);
        }

        $this->status = 0;
        $this->productname .= ' [Deleted]';

        return $this->save(false);
    }

    public function getProductCount()
    {
        return self::find()->where(['status' => 1])->count();
    }

    public function getProductCountByCategory($categoryId)
    {
        return self::find()->where(['categoryid' => $categoryId, 'status' => 1])->count();
    }
}
