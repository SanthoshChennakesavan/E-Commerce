<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\Products $model */

$this->title = 'View Product';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['product']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container mt-4">
    <h2><?= Html::encode($this->title) ?></h2>

    <div class="card p-4 mt-3">
        <p><strong>ID:</strong> <?= $model->id ?></p>
        <p><strong>Product Name:</strong> <?= Html::encode($model->productname) ?></p>
        <p><strong>Description:</strong> <?= Html::encode($model->productdes) ?></p>
        <p><strong>Price:</strong> ₹<?= number_format($model->productprice) ?></p>
        <p><strong>Stock:</strong> <?= $model->stock ?></p>
        <p><strong>Minimum Quantity:</strong> <?= Html::encode($model->min_quantity) ?></p>
        <p><strong>Maximum Quantity:</strong> <?= Html::encode($model->max_quantity) ?></p>
        <p><strong>Category:</strong> <?= Html::encode($model->category->categoryname ?? 'N/A') ?></p>
        <p><strong>Status:</strong> <?= $model->status == 1 ? 'Active' : 'Inactive' ?></p>
        <p><strong>Image:</strong><br>
            <?php if ($model->productimage): ?>
                <?= Html::img(Yii::getAlias('@web/uploads/' . $model->productimage), ['style' => 'max-width: 150px; border: 1px solid #ccc; padding: 4px;']) ?>
            <?php else: ?>
                (No image)
            <?php endif; ?>
        </p>

        <?= Html::a('Back to List', ['product'], ['class' => 'btn btn-secondary mt-3']) ?>
    </div>
</div>
