<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\modules\admin\models\Category;
use app\modules\admin\models\Products;
      
class DefaultController extends Controller
{
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['default/dashboard']);
        }
        $this->layout = 'admin';
        $model = new User();
        
        if ($model->load(Yii::$app->request->post())) {
            $result = $model->adminLogin();

            if($result['success']){
                Yii::$app->session->setFlash('success', $result['message']);
                return $this->redirect(['dashboard']);
            }
            else{
                Yii::$app->session->setFlash('error', $result['message']);
                return $this->redirect(['index']);
            }
        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionDashboard()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['default/index']);
        }

        $this->layout = 'dashboard';

        $categoryModel = new Category();
        $productModel = new Products();

        $categoryCount = $categoryModel->getCategoryCount();
        $productCount = $productModel->getProductCount();

        $chartData = $categoryModel->getPieChartData();

        return $this->render('dashboard', [
            'categoryCount' => $categoryCount,
            'productCount' => $productCount,
            'pieLabels' => $chartData['labels'],
            'pieData' => $chartData['data'],
            'barLabels' => $chartData['labels'],
            'barData' => $chartData['data'],
        ]);
    }
  
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['index']);
    }

}






