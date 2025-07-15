<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Create Admin';
$this->params['breadcrumbs'][] = ['label' => 'Admin', 'url' => ['admin-index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="adminuser-create container mt-4">

        <?php if (Yii::$app->session->hasFlash('success')): ?>
    <?php endif; ?>
    <h2><?= Html::encode($this->title) ?></h2>

    <div class="card p-4 mt-3">
        
        <?php $form = ActiveForm::begin(); ?>
           <!-- <style>
            .help-block {
                color: #dc3545;
                font-size: 0.875rem;
                margin-top: 5px;
            }
        </style> -->

        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'status')->widget(Select2::classname(), [
            'bsVersion' => '5.x',
            'data' => [1 => 'Active', 0 => 'Inactive'],
            'options' => ['placeholder' => 'Select Status'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?> 

        <div class="form-group mt-3">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['admin-index'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
