<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\modules\admin\models\Category;
use yii\web\Response;
use yii\web\BadRequestHttpException;

class CategoryController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->redirect(['default/index']);
        }
        return parent::beforeAction($action);
    }

    public function actionCategory()
    {
        $this->layout = 'dashboard';

        $categoryname = Yii::$app->request->post('categoryname');
        $dataProvider = Category::getFilteredCategoryList($categoryname);

        return $this->render('category', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $this->layout = 'dashboard';
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Category created successfully.');
            return $this->redirect(['category']); 
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionView($id)
    {
        $this->layout = 'dashboard';
        $model = Category::findOne($id);
        return $this->render('viewcategory', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $this->layout = 'dashboard';
        $model = Category::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Category updated successfully.');
            return $this->redirect(['category']); 
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try
        {
            if (!Yii::$app->request->isPost) {
                throw new BadRequestHttpException('Invalid request method.');
            }

            $model = Category::findOne($id);
            if (!$model) {
                Yii::$app->session->setFlash('error', 'Category not found.');
                return ['success' => true];
            }

            $model->softDeleteWithCheck();
            return ['success' => true];

        } 
        catch (\Throwable $e) 
        {
            Yii::error("Delete category error: " . $e->getMessage(), __METHOD__);
            Yii::$app->session->setFlash('error', 'An error occurred while deleting the category.');
            return ['success' => true];
        }
    }
}
