<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Login';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h3 class="mb-4"><?= Html::encode($this->title) ?></h3>

           <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
                <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
                    <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endforeach; ?>


            <?php $form = ActiveForm::begin(); ?>
            <!-- <style>
            .help-block {
                            color: #dc3545;
                            font-size: 0.875rem;
                            margin-top: 5px;
                        }
            </style>           -->

            <?= $form->field($model, 'username')->textInput([
                'placeholder' => 'Enter your username',
                'class' => 'form-control form-control-lg',
                'autofocus' => true
            ]) ?>

            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => 'Enter your password',
                'class' => 'form-control form-control-lg'
            ]) ?>

            <div class="form-group mt-3">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-lg w-100']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


