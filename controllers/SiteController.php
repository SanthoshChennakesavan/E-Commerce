<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Category;
use app\modules\admin\models\Products;
use app\components\MailHelper;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $selectedCategories = Yii::$app->request->get('category', []);
        if (!is_array($selectedCategories)) {
            $selectedCategories = [$selectedCategories];
        }

        $searchModel = new Products();
        $dataProvider = $searchModel->productSearch($selectedCategories);

        $categories = Category::find()->where(['status' => 1])->all();

        $wishlistProductIds = [];
        if (!Yii::$app->user->isGuest) {
            $wishlistProductIds = \app\models\Wishlist::find()
                ->select('product_id')
                ->where(['user_id' => Yii::$app->user->id])
                ->column();
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'categories' => $categories,
            'selectedCategories' => $selectedCategories,
            'wishlistProductIds' => $wishlistProductIds,
        ]);
    }

    public function actionSignup()
    {
        $model = new User();
        $model->scenario = 'user';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->status = 1;
            $model->user_type = 1;
            $model->is_verified = 0;
            $model->generateOtp();

            if ($model->save(false)) {
                $body = "
                    <p>Hello <strong>{$model->fullname}</strong>,</p>
                    <p>Your OTP for account verification is: <strong>{$model->otp}</strong></p>
                    <p>This OTP is valid for 5 minutes.</p>
                ";

                $sent = MailHelper::send(
                    $model->email,
                    'OTP Verification',
                    $body,
                    Yii::$app->params['smtpUser'],
                    'Ecom App'
                );

                if ($sent) {
                    Yii::$app->session->setFlash('success', 'OTP sent to your email.');
                    return $this->redirect(['site/verify-otp', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Signup successful but failed to send OTP email.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Failed to save user data.');
            }
        }

        return $this->render('signup', ['model' => $model]);
    }

    public function actionVerifyOtp($id)
    {
        $model = User::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException('User not found.');
        }

        $otpExpired = time() > $model->otp_expiry;

        if (Yii::$app->request->isPost && !$otpExpired) {
            $enteredOtp = Yii::$app->request->post('otp');

            if ($enteredOtp == $model->otp) {
                $model->is_verified = 1;
                $model->otp = null;
                $model->otp_expiry = null;
                $model->save(false);

                Yii::$app->session->setFlash('success', 'OTP verified successfully. You can now log in.');
                return $this->redirect(['site/login']);
            } else {
                Yii::$app->session->setFlash('error', 'Invalid OTP.');
            }
        } elseif ($otpExpired && Yii::$app->request->isPost) {
            Yii::$app->session->setFlash('error', 'OTP expired.');
        }

        return $this->render('verify-otp', [
            'model' => $model,
            'otpExpirySeconds' => max($model->otp_expiry - time(), 0),
            'otpIsExpired' => $otpExpired,
        ]);
    }

    public function actionResendOtp($id)
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new \yii\web\NotFoundHttpException('User not found.');
        }

        if ($user->is_verified) {
            Yii::$app->session->setFlash('info', 'Your account is already verified.');
            return $this->redirect(['site/login']);
        }

        $user->generateOtp();
        if ($user->save(false)) {
            $body = "
                <p>Hello <strong>{$user->fullname}</strong>,</p>
                <p>Your new OTP is: <strong>{$user->otp}</strong></p>
                <p>This OTP is valid for 5 minutes.</p>
            ";

            $sent = MailHelper::send(
                $user->email,
                'Resend OTP',
                $body,
                Yii::$app->params['smtpUser'],
                'Ecom App'
            );

            if ($sent) {
                Yii::$app->session->setFlash('success', 'OTP has been resent to your email.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to resend OTP email.');
            }
        }

        return $this->redirect(['site/verify-otp', 'id' => $user->id]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }

        $model = new User();
        $model->scenario = 'admin';


        if ($model->load(Yii::$app->request->post())) {
            $inputUsername = $model->username;
            $inputPassword = $model->password;
           

            $user = User::findOne(['username' => $inputUsername]);

            if ($user && $user->validatePassword($inputPassword)) {
                Yii::$app->user->login($user);
                Yii::$app->session->set('id', $user->id);
                return $this->redirect(['site/index']);
            }
        }

        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
