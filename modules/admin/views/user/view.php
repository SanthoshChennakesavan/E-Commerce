<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'View Admin';
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['user-index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container mt-4">
    <h2><?= Html::encode($this->title) ?></h2>

    <div class="card p-4 mt-3">
        <p><strong>ID:</strong> <?= $model->id ?></p>
        <p><strong>Username:</strong> <?= Html::encode($model->username) ?></p>
        <p><strong>Fullname:</strong> <?= Html::encode($model->fullname) ?></p>
        <p><strong>Email:</strong> <?= Html::encode($model->email) ?></p>
        <p><strong>Phone:</strong> <?= Html::encode($model->phone) ?></p>
        <p><strong>DOB:</strong> <?= Html::encode($model->dob) ?></p>
        <p><strong>Address:</strong> <?= Html::encode($model->address) ?></p>
        <p><strong>District:</strong> <?= Html::encode($model->district) ?></p>
        <p><strong>State:</strong> <?= Html::encode($model->state) ?></p>
        <p><strong>Gender:</strong> <?= Html::encode($model->gender) ?></p>
        <p><strong>Password:</strong> <?= Html::encode($model->password) ?></p>
        <p><strong>Status:</strong> <?= $model->status == 1 ? 'Active' : 'Inactive' ?></p>

        <?= Html::a('Back to List', ['user-index'], ['class' => 'btn btn-secondary mt-3']) ?>
    </div>
</div>
