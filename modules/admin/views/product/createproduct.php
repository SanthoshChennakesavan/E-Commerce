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

$categories = ArrayHelper::map(Category::find()->where(['status' => 1])->all(), 'id', 'categoryname');
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
            'pluginOptions' => ['allowClear' => true],
        ]) ?>

        <?= $form->field($model, 'productname')->textInput(['maxlength' => true]) ?>

        <!-- <style>
            .help-block {
                color: #dc3545;
                font-size: 0.875rem;
                margin-top: 5px;
            }
        </style> -->

        <?= $form->field($model, 'productdes')->textarea(['rows' => 3]) ?>

        <?= $form->field($model, 'productprice')->textInput([
            'type' => 'number', 'min' => '0', 'step' => '0.01',
        ]) ?>

        <?= $form->field($model, 'stock')->textInput([
            'type' => 'number', 'min' => '0', 'step' => '1',
        ]) ?>

        <?= $form->field($model, 'min_quantity')->textInput([
            'type' => 'number', 'min' => '0', 'step' => '1',
        ]) ?>

        <?= $form->field($model, 'max_quantity')->textInput([
            'type' => 'number', 'min' => '0', 'step' => '1',
        ]) ?>

        <?= $form->field($model, 'status')->widget(Select2::class, [
            'bsVersion' => '5',
            'data' => [1 => 'Active', 0 => 'Inactive'],
            'options' => ['placeholder' => 'Select Status'],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>

        <?= $form->field($model, 'imageFile')->fileInput([
            'id' => 'imageUpload'
        ]) ?>

        <div class="mt-3">
            <label><strong>Preview:</strong></label><br>
            <img id="imagePreview" src="#" alt="Selected Image"
                 style="max-width: 200px; display: none; border: 1px solid #ccc; padding: 5px;" />
        </div>

        <?php if (!$model->isNewRecord && $model->productimage): ?>
            <div class="mt-3" id="currentImageWrapper">
                <label><strong>Current Image:</strong></label><br>
                <img src="<?= Yii::getAlias('@web') . '/uploads/' . $model->productimage ?>"
                     alt="Current Image"
                     style="max-width: 200px; border: 1px solid #ccc; padding: 5px;">
                <p class="text-muted mt-2"><?= Html::encode($model->productimage) ?></p>
            </div>
        <?php endif; ?>

        <div class="form-group mt-3">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['product'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$js = <<<JS
document.getElementById("imageUpload").addEventListener("change", function(event) {
    const input = event.target;
    const preview = document.getElementById("imagePreview");
    const currentImage = document.getElementById("currentImageWrapper");

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
            if (currentImage) {
                currentImage.style.display = "none";
            }
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = "#";
        preview.style.display = "none";
        if (currentImage) {
            currentImage.style.display = "block";
        }
    }
});
JS;

$this->registerJs($js);
?>
