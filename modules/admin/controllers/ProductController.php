<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\BadRequestHttpException;
use app\modules\admin\models\Products;

class ProductController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['default/index']);
        }
        return parent::beforeAction($action);
    }

    public function actionProduct()
    {
        $this->layout = 'dashboard';
        $productname = Yii::$app->request->post('productname');
        $dataProvider = Products::getFilteredProductList($productname);

        return $this->render('product', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateproduct()
    {
        $this->layout = 'dashboard';
        $model = new Products();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->handleUploadAndSave()) {
                Yii::$app->session->setFlash('success', 'Product created successfully.');
                return $this->redirect(['product']);
            } else {
                Yii::$app->session->setFlash('error', 'Upload failed.');
                Yii::error($model->errors, 'product.create');
            }
        }

        return $this->render('createproduct', ['model' => $model]);
    }

    public function actionView($id)
    {
        $this->layout = 'dashboard';
        $model = Products::findOne($id);
        return $this->render('viewproduct', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $this->layout = 'dashboard';
        $model = Products::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            $model->handleUploadAndSave();

            Yii::$app->session->setFlash('success', 'Product updated successfully.');
            return $this->redirect(['product']);
        }

        return $this->render('createproduct', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->request->isPost) {
            throw new BadRequestHttpException('Invalid request method.');
        }

        $model = Products::findOne($id);

        if ($model && $model->softDelete()) {
            Yii::$app->session->setFlash('success', 'Product deleted successfully.');
            return ['success' => true];
        }

        return ['success' => false];
    }
}
