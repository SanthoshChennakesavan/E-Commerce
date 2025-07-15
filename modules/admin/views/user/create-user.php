<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;

$this->title = 'User Sign Up';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4"><?= Html::encode($this->title) ?></h2>

            <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
                <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
                    <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endforeach; ?>

            <?php $form = ActiveForm::begin([
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'options' => ['novalidate' => 'novalidate'],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n<div class='text-danger'>{error}</div>",
                    'labelOptions' => ['class' => 'form-label'],
                ],
            ]); ?>

            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'username')->textInput() ?></div>
                <div class="col-md-6"><?= $form->field($model, 'fullname')->textInput() ?></div>
            </div>

            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'email')->textInput(['type' => 'email']) ?></div>
                <div class="col-md-6">
                    <?= $form->field($model, 'phone')->textInput([
                        'type' => 'tel',
                        'maxlength' => 10,
                        'pattern' => '\d{10}',
                        'oninput' => "this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'dob')->widget(DatePicker::class, [
                    'bsVersion' => '5.x',
                    'options' => ['placeholder' => 'Select your birth date...'],
                    'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']
                ]) ?></div>

                <div class="col-md-6"><?= $form->field($model, 'gender')->widget(Select2::class, [
                    'bsVersion' => '5.x',
                    'data' => ['Male' => 'Male', 'Female' => 'Female'],
                    'options' => ['placeholder' => 'Select Gender'],
                    'pluginOptions' => ['allowClear' => true],
                ]) ?></div>
            </div>

            <div class="row">
                <div class="col-md-12"><?= $form->field($model, 'address')->textarea(['rows' => 2]) ?></div>
            </div>

            <div class="row">
                <div class="col-md-4"><?= $form->field($model, 'district')->textInput() ?></div>
                <div class="col-md-4">
                    <?= $form->field($model, 'state')->widget(Select2::class, [
                        'bsVersion' => '5.x',
                        'data' => [
                            'Tamil Nadu' => 'Tamil Nadu',
                            'Kerala' => 'Kerala',
                            'Karnataka' => 'Karnataka',
                            'Andhra Pradesh' => 'Andhra Pradesh'
                        ],
                        'options' => ['placeholder' => 'Select State'],
                        'pluginOptions' => ['allowClear' => true],
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'pincode')->textInput([
                        'maxlength' => 6,
                        'oninput' => "this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);"
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'password')->passwordInput() ?></div>
            </div>

            <div class="form-group mt-3">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Cancel', ['user-index'], ['class' => 'btn btn-secondary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
