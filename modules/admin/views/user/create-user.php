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
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
                </div>
            <?php endforeach; ?>

            <?php $form = ActiveForm::begin([
                'errorCssClass' => 'is-invalid',
                'fieldConfig' => [
                    'errorOptions' => ['class' => 'text-danger'],
                    'template' => "{label}\n{input}\n<div>{error}</div>",
                ],
            ]); ?>

            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'username')->label('Username <span class="text-danger">*</span>', ['class' => 'form-label']) ?></div>
                <div class="col-md-6"><?= $form->field($model, 'fullname')->label('Fullname <span class="text-danger">*</span>', ['class' => 'form-label']) ?></div>
            </div>

            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'email')->label('Email <span class="text-danger">*</span>', ['class' => 'form-label']) ?></div>
                <div class="col-md-6"><?= $form->field($model, 'phone')->textInput(['type' => 'number', 'min' => '0'])->label('Phone <span class="text-danger">*</span>', ['class' => 'form-label']) ?></div>
            </div>

            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'dob')->widget(DatePicker::classname(), [
                     'bsVersion' => '5.x',
                    'options' => ['placeholder' => 'Select your birth date...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ],
                ])->label('DOB <span class="text-danger">*</span>', ['class' => 'form-label']) ?></div>

                <div class="col-md-6">
                    <?= $form->field($model, 'gender')->widget(Select2::classname(), [
                         'bsVersion' => '5.x',
                        'data' => ['Male' => 'Male', 'Female' => 'Female'],
                        'options' => ['placeholder' => 'Select Gender'],
                        'pluginOptions' => ['allowClear' => true],
                    ])->label('Gender <span class="text-danger">*</span>', ['class' => 'form-label']) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12"><?= $form->field($model, 'address')->textarea(['rows' => 2])->label('Address <span class="text-danger">*</span>', ['class' => 'form-label']) ?></div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'district')->label('District <span class="text-danger">*</span>', ['class' => 'form-label']) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'state')->widget(Select2::classname(), [
                        'data' => [
                            'Tamil Nadu' => 'Tamil Nadu',
                            'Kerala' => 'Kerala',
                            'Karnataka' => 'Karnataka',
                            'Andhra Pradesh' => 'Andhra Pradesh'
                        ],
                         'bsVersion' => '5.x',
                        'options' => ['placeholder' => 'Select State'],
                        'pluginOptions' => ['allowClear' => true],
                    ])->label('State <span class="text-danger">*</span>', ['class' => 'form-label']) ?>
                </div>
                <div class="col-md-4"><?= $form->field($model, 'pincode')->label('Pincode <span class="text-danger">*</span>', ['class' => 'form-label']) ?></div>
            </div>

            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'password')->passwordInput()->label('Password <span class="text-danger">*</span>', ['class' => 'form-label']) ?></div>
            </div>

             <div class="form-group mt-3">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Cancel', ['user-index'], ['class' => 'btn btn-secondary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
