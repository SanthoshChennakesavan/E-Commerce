<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;

$searchKeyword = Yii::$app->request->get('categoryname');
?>

<style>
    .search-toggle { cursor: pointer; }
    .search-form { display: none; }
</style>

<div class="category-index container mt-4">
   <?php foreach (['success', 'error', 'info', 'warning'] as $type): ?>
    <?php if (Yii::$app->session->hasFlash($type)): ?>
        <?php
            $alertClass = match($type) {
                'error' => 'danger',
                default => $type,
            };
        ?>
        <div class="alert alert-<?= $alertClass ?> alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash($type) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php endforeach; ?>

    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h2>Manage Categories</h2>
        </div>
        <div class="col-md-6 text-end">
            <span class="search-toggle btn me-2">
                <i class="bi bi-search"></i>
            </span>
            <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success me-2']) ?>
            <?= Html::a('Back', ['default/dashboard'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?php Pjax::begin(); ?>

    <div class="row mb-3 search-form">
    <div class="col-md-12">
        <?= Html::beginForm(['category'], 'get', ['data-pjax' => 1, 'class' => 'form-inline d-flex gap-2']) ?>
            <?= Html::textInput('categoryname', $searchKeyword, [
                'class' => 'form-control',
                'placeholder' => 'Category name',
            ]) ?>
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Reset', ['category'], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::endForm() ?>
    </div>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        // 'id',
        'categoryname',
        'categorydes',
        [
            'attribute' => 'status',
            'value' => fn($model) => $model->status == 1 ? 'Active' : 'Inactive',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Actions',
            'template' => '{view} {update} {delete}',
        ],
    ],
]); ?>

    <?php Pjax::end(); ?>
</div>

<?php
$js = <<<JS

if (performance.navigation.type === 1) {
    sessionStorage.setItem('categorySearchVisible', '0');
}

$('.search-toggle').on('click', function() {
    $('.search-form').slideToggle();
    const isVisible = $('.search-form').is(':visible');
    sessionStorage.setItem('categorySearchVisible', isVisible ? '1' : '0');
});


function restoreCategorySearchForm() {
    const visible = sessionStorage.getItem('categorySearchVisible') === '1';
    if (visible) {
        $('.search-form').show();
    } else {
        $('.search-form').hide();
    }
}

restoreCategorySearchForm();

$(document).on('pjax:end', function() {
    restoreCategorySearchForm();
});
JS;

$this->registerJs($js);
?>


