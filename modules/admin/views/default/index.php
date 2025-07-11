<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Admin Login';
?>

<style>
    html, body {
        height: 100%;
        margin: 0;
        background-color: #f8f9fa;
    }
    
    input::placeholder {
        font-size: 14px;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 6px;
    }

    .help-block {
        color: red;
        font-size: 0.875rem;
        margin-top: 5px;
    }
</style>

<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="col-md-4">
        <div class="card border border-secondary shadow rounded-4">
            <div class="card-body px-4 py-4">

            <?php foreach (['success', 'error', 'info', 'warning'] as $type): ?>
                <?php if (Yii::$app->session->hasFlash($type)): ?>
                    <?php
                        $bootstrapAlertType = [
                            'success' => 'alert-success',
                            'error'   => 'alert-danger',
                            'info'    => 'alert-info',
                            'warning' => 'alert-warning',
                        ][$type];
                    ?>
                    <div class="alert <?= $bootstrapAlertType ?> alert-dismissible fade show text-center" role="alert">
                        <?= Yii::$app->session->getFlash($type) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
                <h4 class="mb-4 text-center"><?= Html::encode($this->title) ?></h4>

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'username')->textInput([
                    'autofocus' => true,
                    'placeholder' => 'Enter your username',
                    'class' => 'form-control form-control-lg'
                ])->label('Username <span class="text-danger">*</span>', ['class' => 'form-label']) ?>

                <?= $form->field($model, 'password')->passwordInput([
                    'placeholder' => 'Enter your password',
                    'class' => 'form-control form-control-lg'
                ])->label('Password <span class="text-danger">*</span>', ['class' => 'form-label']) ?>

                <div class="form-group d-grid mt-3">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-lg','style'=>'width: 150px; margin: 0 auto;']) ?>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

