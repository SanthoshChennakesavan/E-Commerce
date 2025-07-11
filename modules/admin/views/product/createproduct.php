<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\admin\models\Category;
use kartik\select2\Select2;
use yii\helpers\Url;

$this->title = 'Create Product';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['product']];
$this->params['breadcrumbs'][] = $this->title;

$categories = ArrayHelper::map(Category::find()->all(), 'id', 'categoryname');
?>

<div class="product-create container mt-4">
    <h2><?= Html::encode($this->title) ?></h2>

    <div class="card p-4 mt-3">
        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>

        <?= $form->field($model, 'categoryid')->widget(Select2::class, [
            'bsVersion' => '5',
            'data' => $categories,
            'options' => ['placeholder' => 'Select Category'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>

        <?= $form->field($model, 'productname')->textInput(['maxlength' => true]) ?>
        <style>
            .help-block {
                color: #dc3545; 
                font-size: 0.875rem;
                margin-top: 5px;
            }
        </style>
        <?= $form->field($model, 'productdes')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'productprice')->textInput([
            'type' => 'number',
            'min' => '0',
            'step' => '0.01', 
        ]) ?>
        <?= $form->field($model, 'stock')->textInput([
            'type' => 'number',
            'min' => '0',
            'step' => '1',
        ]) ?>

        <?= $form->field($model, 'min_quantity')->textInput([
            'type' => 'number',
            'min' => '0',
            'step' => '1',
        ]) ?>

        <?= $form->field($model, 'max_quantity')->textInput([
            'type' => 'number',
            'min' => '0',
            'step' => '1',
        ]) ?>

        <?= $form->field($model, 'status')->widget(Select2::class, [
            'bsVersion' => '5',
            'data' => [1 => 'Active', 0 => 'Inactive'],
            'options' => ['placeholder' => 'Select Status'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>

        <?= $form->field($model, 'imageFile')->fileInput() ?>
        <br>
          <?php if (!$model->isNewRecord && $model->productimage): ?>
                    <p>
                        Current Image: 
                        <a href="<?= Url::to("@webroot/uploads/{$model->productimage}") ?>" target="_blank">
                            <?= Html::encode($model->productimage) ?>
                        </a>
                    </p>
               <?php endif;?>


        <div class="form-group mt-3">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['product'], ['class' => 'btn btn-secondary']) ?>
        </div>
      
        <?php ActiveForm::end(); ?>
    </div>
</div>
