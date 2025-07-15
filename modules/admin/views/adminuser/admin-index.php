<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

$user = Yii::$app->user->identity;
$usertype = $user?->user_type ?? null;

$template = '';

if ($usertype == 3) {
    $template = '{view} {update} {delete}';
} else {
    if ($user?->hasPermission('view_admin')) {
        $template .= ' {view}';
    }
    if ($user?->hasPermission('update_admin')) {
        $template .= ' {update}';
    }
    if ($user?->hasPermission('delete_admin')) {
        $template .= ' {delete}';
    }
}
$template = trim($template); 
?>

<div class="Admin-index container mt-4">
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
            <h2>Manage Admin</h2>
        </div>
        <div class="col-md-6 text-end">
            <?php if ($usertype == 3 || $user?->hasPermission('create_admin')): ?>
                <?= Html::a('Create Admin', ['/admin/adminuser/create-admin'], ['class' => 'btn btn-success me-2']) ?>
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
            [
                'attribute' => 'status',
                'value' => fn($model) => $model->status == 1 ? 'Active' : 'Inactive',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'template' => $template,
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="bi bi-eye text-primary"></i>', $url, [
                            'title' => 'View Admin',
                            'data-pjax' => '0',
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<i class="bi bi-pencil-square text-success"></i>', $url, [
                            'title' => 'Update Admin',
                            'data-pjax' => '0',
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="bi bi-trash text-danger"></i>', 'javascript:void(0)', [
                            'title' => 'Delete Admin',
                            'class' => 'ajax-delete-admin',
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

$deleteJs = <<<JS
$(document).on('click', '.ajax-delete-admin', function(e) {
    e.preventDefault();
    const url = $(this).data('url');
    const confirmed = confirm('Are you sure you want to delete this admin?');
    if (!confirmed) return;

    $.ajax({
        url: url,
        type: 'POST',
        success: function(response) {
            if (response.success) {
                $.pjax.reload({container: '#p0'}); 
            } else {
                alert(response.message || 'Deletion failed.');
            }
        },
        error: function() {
            alert('An error occurred while deleting.');
        }
    });
});
JS;

$this->registerJs($deleteJs);
?>

</div>
