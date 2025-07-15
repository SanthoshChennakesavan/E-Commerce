<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$user = Yii::$app->user->identity;
$usertype = $user?->user_type ?? null;

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;

$searchKeyword = Yii::$app->request->post('categoryname');

$template = '';
if ($usertype == 3) {
    $template = '{view} {update} {delete}';
} else {
    if ($user?->hasPermission('view_category')) {
        $template .= ' {view}';
    }
    if ($user?->hasPermission('update_category')) {
        $template .= ' {update}';
    }
    if ($user?->hasPermission('delete_category')) {
        $template .= ' {delete}';
    }
}
$template = trim($template);
?>

<div class="category-index container mt-4">

    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h2>Manage Categories</h2>
        </div>
        <div class="col-md-6 text-end">
            <span class="search-toggle btn me-2">
                <i class="bi bi-search"></i>
            </span>
            <?php if ($usertype == 3 || $user?->hasPermission('create_category')): ?>
                <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success me-2']) ?>
            <?php endif; ?>
            <?= Html::a('Back', ['default/dashboard'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?php Pjax::begin(['id' => 'category-pjax']); ?>

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
    <div class="row mb-3 search-form" style="display: none;">
        <div class="col-md-12">
            <?= Html::beginForm(['category'], 'post', [
                'data-pjax' => 1,
                'id' => 'search-form',
                'class' => 'form-inline d-flex gap-2'
            ]) ?>
                <?= Html::textInput('categoryname', $searchKeyword, [
                    'class' => 'form-control',
                    'placeholder' => 'Category name'
                ]) ?>
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                <?= Html::button('Reset', ['class' => 'btn btn-outline-secondary', 'id' => 'reset-btn']) ?>
            <?= Html::endForm() ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'categoryname',
            'categorydes',
            [
                'attribute' => 'status',
                'value' => fn($model) => $model->status == 1 ? 'Active' : 'Inactive',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'template' => $template,
                'buttons' => [
                    'delete' => fn($url, $model) =>
                        Html::a('<i class="bi bi-trash text-danger"></i>', 'javascript:void(0)', [
                            'class' => 'ajax-delete ms-1',
                            'data-id' => $model->id,
                            'title' => 'Delete',
                        ]),
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>

<?php
$this->registerJs(<<<JS
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
    bindResetButton();
});

// AJAX Delete
$(document).on('click', '.ajax-delete', function(e) {
    e.preventDefault();
    if (!confirm('Are you sure you want to delete this category?')) return;

    var id = $(this).data('id');

    $.ajax({
        url: 'delete?id=' + id,
        type: 'POST',
        success: function(response) {
           if (response.success) {
                $.pjax.reload({container: '#category-pjax'});
            }
        },
        error: function() {
            alert('Error occurred while deleting.');
        }
    });
});

// Reset Button
function bindResetButton() {
    $('#reset-btn').off('click').on('click', function() {
        $('#search-form input[name="categoryname"]').val('');
        $.pjax.reload({
            container: '#category-pjax',
            method: 'POST',
            data: { categoryname: '' }
        });
    });
}
bindResetButton();
JS);
?>
