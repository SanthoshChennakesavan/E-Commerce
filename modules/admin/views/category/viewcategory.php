<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\Category $model */

$this->title = 'View Category';
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['category']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container mt-4">
    <h2><?= Html::encode($this->title) ?></h2>

    <div class="card p-4 mt-3">
        <p><strong>ID:</strong> <?= $model->id ?></p>
        <p><strong>Name:</strong> <?= Html::encode($model->categoryname) ?></p>
        <p><strong>Description:</strong> <?= Html::encode($model->categorydes) ?></p>
        <p><strong>Status:</strong> <?= $model->status == 1 ? 'Active' : 'Inactive' ?></p>

        <?= Html::a('Back to List', ['category'], ['class' => 'btn btn-secondary mt-3']) ?>
    </div>
</div>
