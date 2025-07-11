<?php namespace app\modules\admin\models;

use Yii;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;

class Products extends \yii\db\ActiveRecord
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
            [['categoryid', 'productname', 'productdes', 'productprice', 'stock', 'status'], 'required'],
            [['productprice', 'stock'], 'number'],
            [['categoryid', 'status'], 'integer'],
            [['min_quantity', 'max_quantity'], 'integer'],
            [['productname'], 'string', 'max' => 100],
            [['productname'], 'validateUniqueProductName'], // ✅ custom validator
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

   public function upload()
    {
        if ($this->validate()) {
            $fileName = uniqid() . '.' . $this->imageFile->extension;
            $filePath = Yii::getAlias('@webroot/uploads/') . $fileName;

            if ($this->imageFile->saveAs($filePath)) {
                $this->productimage = $fileName;
                return true;
            }
        }
        return false;
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'categoryid']);
    }

    public function productSearch($selectedCategories = null){
        $query = self::find()->where(['status' => 1]);

        if (!empty($selectedCategories)) {
            $query->andWhere(['categoryid' => $selectedCategories]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->with('category'),
            'pagination' => ['pageSize' => 8],
        ]);

        return $dataProvider;
    }

}
?>