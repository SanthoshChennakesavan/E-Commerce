<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\modules\admin\models\Category;
use yii\web\Response;

class CategoryController extends Controller
{
    public function actionCategory()
    {
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

        $this->layout = 'dashboard';

        $query = Category::find();

        $categoryname = Yii::$app->request->get('categoryname');
        if (!empty($categoryname)) {
            $query->andFilterWhere(['like', 'categoryname', $categoryname]);
        }

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('category', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

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
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

        $this->layout = 'dashboard';
        $model = Category::findOne($id);
        return $this->render('viewcategory', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

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
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

        $model = Category::findOne($id);

        if ($model) {
            $productCount = \app\modules\admin\models\Products::find()->where(['categoryid' => $id])->count();

            if ($productCount > 0) {
                Yii::$app->session->setFlash('error', 'Cannot delete category. It is assigned to one or more products.');
            } else {
                $model->delete();
                Yii::$app->session->setFlash('success', 'Category deleted.');
            }
        }

        return $this->redirect(['category']);
    }
}