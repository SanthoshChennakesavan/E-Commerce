<?php 

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

$user = Yii::$app->user->identity;
$usertype = $user?->user_type ?? null;

// RBAC-based Action Template
$template = ($usertype == 3) ? '{view} {update} {delete}' : '';

if ($user?->hasPermission('view_user')) {
    $template .= ' {view}';
}
if ($user?->hasPermission('update_user')) {
    $template .= ' {update}';
}
if ($user?->hasPermission('delete_user')) {
    $template .= ' {delete}';
}
$template = trim($template);
?>

<div class="User-index container mt-4">
    <?php foreach (['success', 'error', 'info', 'warning'] as $type): ?>
        <?php if (Yii::$app->session->hasFlash($type)): ?>
            <?php $alertClass = match($type) {
                'error' => 'danger',
                default => $type,
            }; ?>
            <div class="alert alert-<?= $alertClass ?> alert-dismissible fade show" role="alert">
                <?= Yii::$app->session->getFlash($type) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>   

    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h2>Manage Users</h2>
        </div>
        <div class="col-md-6 text-end">
            <?php if ($usertype == 3 || $user?->hasPermission('create_user')): ?>
                <?= Html::a('Create User', ['/admin/user/create-user'], ['class' => 'btn btn-success me-2']) ?>
            <?php endif; ?>
            <?= Html::a('Back', ['default/dashboard'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            'fullname',
            'email',
            'phone',
            'dob',
            'address',
            'district',
            'pincode',
            'state',
            'gender',
            [
                'attribute' => 'status',
                'value' => fn($model) => $model->status == 1 ? 'Active' : 'Inactive',
            ],
           [
    'class' => 'yii\grid\ActionColumn',
    'header' => 'Actions',
    'template' => $template,
    'buttons' => [
        'delete' => function ($url, $model, $key) {
            return Html::a('<i class="bi bi-trash text-danger"></i>', 'javascript:void(0)', [
                'title' => 'Delete User',
                'class' => 'ajax-delete-user',
                'data-id' => $model->id,
                'data-url' => Url::to(['delete', 'id' => $model->id]),
            ]);
        },
    ],
],

        ]
    ]); ?>

    <?php Pjax::end(); ?>
    <?php
$js = <<<JS
$(document).on('click', '.ajax-delete-user', function(e) {
    e.preventDefault();
    const url = $(this).data('url');
    const confirmed = confirm('Are you sure you want to delete this user?');
    if (!confirmed) return;

    $.ajax({
        url: url,
        type: 'POST',
        success: function(response) {
            if (response.success) {
                $.pjax.reload({container: '#p0'}); // Make sure this ID matches your PJAX container
            } else {
                alert(response.message || 'Failed to delete.');
            }
        },
        error: function() {
            alert('An error occurred during delete.');
        }
    });
});
JS;

$this->registerJs($js);
?>

</div>
