<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;


/** @var yii\web\View $this */
/** @var app\modules\admin\models\Category $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Create Category';
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['category']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="category-create container mt-4">

        <?php if (Yii::$app->session->hasFlash('success')): ?>
    <?php endif; ?>
    <h2><?= Html::encode($this->title) ?></h2>

    <div class="card p-4 mt-3">
        
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'categoryname')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'categorydes')->textarea(['rows' => 3]) ?>

         <?= $form->field($model, 'status')->widget(Select2::classname(), [
            'bsVersion' => '5.x',
            'data' => [1 => 'Active', 0 => 'Inactive'],
            'options' => ['placeholder' => 'Select Status'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?> 

        <?= $form->field($model, 'category_seourl')->textInput()?>

        <div class="form-group mt-3">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['category'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
