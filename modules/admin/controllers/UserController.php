<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\modules\admin\models\Superadmin;
use app\models\User;

class UserController extends Controller
{
    public function actionUserIndex()
    {
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

        $this->layout = 'dashboard';

        $query = User::find()->where(['user_type'=>0]);

        // $username = Yii::$app->request->get('username');
        // if (!empty($username)) {
        //     $query->andFilterWhere(['like', 'username', $username]);
        // }

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('user-index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

        $this->layout = 'dashboard';
        $model = User::findOne($id);
        return $this->render('view', ['model' => $model]);
    }

    public function actionCreateUser()
    {
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

        $this->layout = 'dashboard';
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'User created successfully.');
            return $this->redirect(['user-index']); 
        }

        return $this->render('create-user', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

        $this->layout = 'dashboard';
        $model = User::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'User updated successfully.');
            return $this->redirect(['user-index']); 
        }

        return $this->render('create-user', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

        $model = User::findOne($id);

        if ($model !== null) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'User deleted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'User not found.');
        }

        return $this->redirect(['user-index']);
    }
}