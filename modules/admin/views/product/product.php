<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$user = Yii::$app->user->identity;
$usertype = $user?->user_type ?? null;

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;

$template = '';
if ($usertype == 3) {
    $template = '{view} {update} {delete}';
} else {
    if ($user?->hasPermission('view_product')) {
        $template .= ' {view}';
    }
    if ($user?->hasPermission('update_product')) {
        $template .= ' {update}';
    }
    if ($user?->hasPermission('delete_product')) {
        $template .= ' {delete}';
    }
}
$template = trim($template);
?>

<div class="product-index container mt-4">

    <?php foreach (['success', 'error', 'info', 'warning'] as $type): ?>
        <?php if (Yii::$app->session->hasFlash($type)): ?>
            <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
                <?= Yii::$app->session->getFlash($type) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h2>Manage Products</h2>
        </div>
        <div class="col-md-6 text-end">
            <span class="search-toggle btn me-2">
                <i class="bi bi-search"></i>
            </span>
            <?php if ($usertype == 3 || $user?->hasPermission('create_product')): ?>
                <?= Html::a('Create Product', ['createproduct'], ['class' => 'btn btn-success me-2']) ?>
            <?php endif; ?>
            <?= Html::a('Back', ['default/dashboard'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

<?php Pjax::begin(['id' => 'product-pjax']); ?>

<div class="row mb-3 search-form" style="display: none;">
    <div class="col-md-12">
        <?= Html::beginForm(['product'], 'post', [
            'data-pjax' => 1,
            'id' => 'search-form',
            'class' => 'form-inline d-flex gap-2'
        ]) ?>
            <?= Html::textInput('productname', '', [
                'class' => 'form-control',
                'placeholder' => 'Product name'
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
        'productname',
        'productdes',
        'productprice',
        'stock',
        'min_quantity',
        'max_quantity',
        [
            'attribute' => 'status',
            'value' => fn($model) => $model->status == 1 ? 'Active' : 'Inactive',
        ],
        [
            'attribute' => 'product_image',
            'format' => 'raw',
            'value' => function ($model) {
                $url = Yii::getAlias('@web') . '/uploads/' . $model->productimage;
                return Html::a(
                    Html::img($url, ['width' => '50', 'class' => 'preview-trigger', 'style' => 'cursor:pointer']),
                    'javascript:void(0)',
                    ['data-image' => $url]
                );
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Actions',
            'template' => $template,
            'buttons' => [
                'view' => fn($url, $model) =>
                    Html::a('<i class="bi bi-eye text-primary"></i>', ['view', 'id' => $model->id], [
                        'class' => 'ms-1',
                        'title' => 'View',
                    ]),
                'update' => fn($url, $model) =>
                    Html::a('<i class="bi bi-pencil text-primary"></i>', ['update', 'id' => $model->id], [
                        'class' => 'ms-1',
                        'title' => 'Update',
                    ]),
                'delete' => fn($url, $model) =>
                    Html::a('<i class="bi bi-trash text-danger"></i>', 'javascript:void(0)', [
                        'class' => 'ajax-delete ms-1',
                        'data-id' => $model->id,
                        'title' => 'Delete',
                        'aria-label' => 'Delete',
                    ]),
            ],
        ],
    ],
]); ?>

<?php Pjax::end(); ?>

</div>

<!-- Image Preview Modal  -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-2">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
if (performance.navigation.type === 1) {
    sessionStorage.setItem('productSearchVisible', '0');
}

$('.search-toggle').on('click', function() {
    $('.search-form').slideToggle();
    const isVisible = $('.search-form').is(':visible');
    sessionStorage.setItem('productSearchVisible', isVisible ? '1' : '0');
});

function restoreSearchForm() {
    const visible = sessionStorage.getItem('productSearchVisible') === '1';
    if (visible) {
        $('.search-form').show();
    } else {
        $('.search-form').hide();
    }
}
restoreSearchForm();

$(document).on('pjax:end', function() {
    restoreSearchForm();
    bindResetButton();
});

// AJAX Delete
$(document).on('click', '.ajax-delete', function(e) {
    e.preventDefault();
    if (!confirm('Are you sure you want to delete this product?')) return;

    var id = $(this).data('id');

    $.ajax({
        url: 'delete?id=' + id,
        type: 'POST',
        success: function(response) {
            if (response.success) {
                $.pjax.reload({container: '#product-pjax'});
            } else {
                alert('Delete failed');
            }
        },
        error: function() {
            alert('Error occurred while deleting.');
        }
    });
});

// Image Preview
$(document).on('click', '.preview-trigger', function () {
    let imageUrl = $(this).closest('a').data('image');
    $('#previewImage').attr('src', imageUrl);
    var myModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
    myModal.show();
});

// Reset Button
function bindResetButton() {
    $('#reset-btn').off('click').on('click', function() {
        $('#search-form input[name="productname"]').val('');
        $.pjax.reload({
            container: '#product-pjax',
            method: 'POST',
            data: { productname: '' }
        });
    });
}
bindResetButton();
JS);
?>
