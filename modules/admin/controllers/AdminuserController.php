<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\modules\admin\models\Superadmin;
use app\models\User;

class AdminuserController extends Controller
{
    public function actionAdminIndex()
    {
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

        $this->layout = 'dashboard';

        $query = User::find();

        // $username = Yii::$app->request->get('username');
        // if (!empty($username)) {
        //     $query->andFilterWhere(['like', 'username', $username]);
        // }

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('admin-index', [
            'dataProvider' => $dataProvider,
        ]);
    }

        public function actionView($id)
        {
            $this->layout = 'dashboard';

            $model = User::findOne($id); 

            if (!$model) {
                throw new \yii\web\NotFoundHttpException("Admin not found.");
            }

            if (Yii::$app->request->isPost) {
                
                $rbacArray = Yii::$app->request->post('rbac', []); 
               
                $model->rbac = json_encode($rbacArray);

                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Permissions saved successfully!');
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to save permissions!');
                }

                return $this->refresh(); 
            }

            return $this->render('view', [
                'model' => $model
        ]);
    }

    public function actionCreateAdmin()
    {
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

        $this->layout = 'dashboard';
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Admin created successfully.');
            return $this->redirect(['admin-index']); 
        }

        return $this->render('create-admin', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

        $this->layout = 'dashboard';
        $model = User::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Admin updated successfully.');
            return $this->redirect(['admin-index']); 
        }

        return $this->render('create-admin', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        if (!Yii::$app->session->has('superadmin')) {
            return $this->redirect(['default/index']);
        }

        $model = User::findOne($id);

        if ($model !== null) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Admin deleted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Admin not found.');
        }

        return $this->redirect(['admin-index']);
    }

}