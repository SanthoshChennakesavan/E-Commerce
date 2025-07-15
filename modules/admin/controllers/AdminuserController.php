<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use app\models\User;
use app\components\MailHelper;
use yii\web\NotFoundHttpException;

class AdminuserController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['default/index']);
        }
        return parent::beforeAction($action);
    }

    public function actionAdminIndex()
    {
        $this->layout = 'dashboard';
        $dataProvider = User::getUserDataProvider(2);

        return $this->render('admin-index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $this->layout = 'dashboard';
        $model = User::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException("Admin not found.");
        }

        if (Yii::$app->request->isPost) {
            $rbacArray = Yii::$app->request->post('rbac', []);
            $model->rbac = json_encode(array_values($rbacArray), JSON_UNESCAPED_UNICODE);

            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Permissions saved successfully!');
                return $this->redirect(['admin-index']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to save permissions!');
            }
        }

        return $this->render('view', ['model' => $model]);
    }

    public function actionCreateAdmin()
    {
        $this->layout = 'dashboard';
        $model = new User(['scenario' => 'admin']);

        if ($model->load(Yii::$app->request->post())) {
            $model->user_type = 2;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Admin created successfully.');
                return $this->redirect(['admin-index']);
            }
        }

        return $this->render('create-admin', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
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
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = User::findOne($id);

        if ($model !== null && $model->softDelete()) {
            return ['success' => true, 'message' => 'Admin deleted successfully.'];
        }

        return ['success' => false, 'message' => 'Admin not found or could not be deleted.'];
    }

}