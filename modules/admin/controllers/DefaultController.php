<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
      
class DefaultController extends Controller
{
    public $layout = '@app/modules/admin/views/layouts/admin';

public function actionIndex()
{
    $model = new User();

    if ($model->load(Yii::$app->request->post())) {
        $user = User::findOne(['username' => $model->username]);

        if (!$user || $user->user_type == 0 || $user->password !== $model->password) {
            Yii::$app->session->setFlash('error', 'Invalid username or password.');
            return $this->redirect(['index']);
        }

        // Successful login
        Yii::$app->session->set('superadmin', $user->id);
        Yii::$app->session->setFlash('success', 'Login successful!');
        return $this->redirect(['dashboard']);
    }

    return $this->render('index', [
        'model' => $model,
    ]);
}



    public function actionDashboard()
    {
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

        $this->layout = 'dashboard';

        $categoryCount = \app\modules\admin\models\Category::find()->count();
        $productCount = \app\modules\admin\models\Products::find()->count();

        $categories = \app\modules\admin\models\Category::find()->all();

        $pieLabels = [];
        $pieData = [];

        foreach ($categories as $category) {
            $pieLabels[] = $category->categoryname;
            $pieData[] = \app\modules\admin\models\Products::find()
                ->where(['categoryid' => $category->id])
                ->count();
        }

        $barLabels = $pieLabels;
        $barData = $pieData;

        return $this->render('dashboard', [
            'categoryCount' => $categoryCount,
            'productCount' => $productCount,
            'pieLabels' => $pieLabels,
            'pieData' => $pieData,
            'barLabels' => $barLabels,
            'barData' => $barData,
        ]);
    }
  
    public function actionLogout()
    {
        Yii::$app->session->destroy();
        return $this->redirect(['index']);
    }

}






