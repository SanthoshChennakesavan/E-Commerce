<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\modules\admin\models\Products;
      
class ProductController extends Controller
{
    public function actionProduct()
    {
        if (!Yii::$app->session->has('superadmin')) {
                return $this->redirect(['default/index']);
            }

        $this->layout = 'dashboard';
        $query = Products::find();

        $productname = Yii::$app->request->get('productname');
        if (!empty($productname)) {
            $query->andFilterWhere(['like', 'productname', $productname]);
        }

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('product', [
            'dataProvider' => $dataProvider,
        ]);
    }

public function actionCreateproduct()
{
    if (!Yii::$app->session->has('superadmin')) {
        return $this->redirect(['default/index']);
    }

    $this->layout = 'dashboard';
    $model = new Products();

    if ($model->load(Yii::$app->request->post())) {
        $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

        if ($model->imageFile && $model->upload()) {
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Product created successfully.');
                return $this->redirect(['product']);
            }
        } else {
          
            Yii::$app->session->setFlash('error', 'Upload failed.');
            Yii::error($model->errors, 'product.create'); 
        }
    } else {
       
        Yii::error($model->errors, 'product.validation');
    }

    return $this->render('createproduct', ['model' => $model]);
}



    public function actionView($id)
    {
        if (!Yii::$app->session->has('superadmin')) {
                return $this->redirect(['default/index']);
            }

        $this->layout = 'dashboard';
        $model = Products::findOne($id);
        return $this->render('viewproduct', ['model' => $model]);
    }


    public function actionUpdate($id)
    {
        if (!Yii::$app->session->has('superadmin')) {
                return $this->redirect(['default/index']);
            }

        $this->layout = 'dashboard';
        $model = Products::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if($model->validate()){
            if ($model->imageFile && $model->upload()) {
                $model->save(false);
            } else {
                $model->save();
            }
        }

            Yii::$app->session->setFlash('success', 'Product updated successfully.');
            return $this->redirect(['product']);
        }

        return $this->render('createproduct', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = \app\modules\admin\models\Products::findOne($id);

        if ($model) {
            $imagePath = Yii::getAlias('@webroot/uploads/') . $model->productimage;
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }

            $model->delete();
            Yii::$app->session->setFlash('success', 'Product deleted successfully.');
        }

        return $this->redirect(['product']);
    }
}






