<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use app\models\User;
use app\components\MailHelper;

class UserController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['default/index']);
        }
        return parent::beforeAction($action);
    }

    public function actionUserIndex()
    {
        $this->layout = 'dashboard';
        $dataProvider = User::getUserDataProvider(1);

        return $this->render('user-index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $this->layout = 'dashboard';
        $model = User::findOne($id);
        return $this->render('view', ['model' => $model]);
    }

    public function actionCreateUser()
    {
        $this->layout = 'dashboard';
        $model = new User(['scenario' => 'user']);

        if ($model->load(Yii::$app->request->post())) {
            $model->user_type = 1;
            if ($model->save()) {

                $body = "Dear {$model->fullname},<br><br>Your account has been created successfully.
                                                <br>Username: <strong>{$model->username}</strong>
                                                <br>Password: <strong>{$model->password}</strong><br>
                                                <br>Thank you for registering.";

                MailHelper::send($model->email, 'Your account has been created!', $body);

                Yii::$app->session->setFlash('success', 'User created successfully.');
                return $this->redirect(['user-index']);
            }
        }

        return $this->render('create-user', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
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
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = User::findOne($id);

        if ($model !== null && $model->softDelete()) {
            return ['success' => true, 'message' => 'User deleted successfully.'];
        }

        return ['success' => false, 'message' => 'User not found or could not be deleted.'];
    }

}