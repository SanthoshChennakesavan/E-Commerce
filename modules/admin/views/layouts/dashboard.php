<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\BootstrapPluginAsset;
use app\components\assets\AppAsset;

AppAsset::register($this);
BootstrapAsset::register($this);
BootstrapPluginAsset::register($this);
$this->beginPage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php if (!Yii::$app->request->isPjax): ?>
        <?php $this->head() ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title ?? 'Admin Panel') ?></title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link href="<?= Yii::getAlias('@web') ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <?php endif; ?>
</head>

<body>
<?php $this->beginBody(); ?>

<?php
$user = Yii::$app->user->identity;
$usertype = $user?->user_type ?? null;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= Url::to(['/admin/default/dashboard']) ?>">
            <img src="<?= Yii::getAlias('@web') ?>/images/amazonlogo.png" alt="Logo" width="40" height="40">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav"> 
            <ul class="navbar-nav mx-auto">

                <?php if ($usertype == 3 || $user?->hasPermission('access_dashboard')): ?>
                    <li class="nav-item">
                        <?= Html::a('Dashboard', ['/admin/default/dashboard'], ['class' => 'nav-link text-white']) ?>
                    </li>
                <?php endif; ?>

                <?php if ($usertype == 3 || $user?->hasPermission('access_admin') || $user?->hasPermission('access_user')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">User Master</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($usertype == 3 || $user?->hasPermission('access_admin')): ?>
                                <li><a class="dropdown-item" href="<?= Url::to(['/admin/adminuser/admin-index']) ?>">Admin</a></li>
                            <?php endif; ?>
                            <?php if ($usertype == 3 || $user?->hasPermission('access_user')): ?>
                                <li><a class="dropdown-item" href="<?= Url::to(['/admin/user/user-index']) ?>">User</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($usertype == 3 || $user?->hasPermission('access_category')): ?>
                    <li class="nav-item">
                        <?= Html::a('Category', ['/admin/category/category'], ['class' => 'nav-link text-white']) ?>
                    </li>
                <?php endif; ?>

                <?php if ($usertype == 3 || $user?->hasPermission('access_product')): ?>
                    <li class="nav-item">
                        <?= Html::a('Product', ['/admin/product/product'], ['class' => 'nav-link text-white']) ?>
                    </li>
                <?php endif; ?>

            </ul>
        </div>

        <div>
            <ul class="navbar-nav ms-lg-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link text-white" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-5"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li>
                            <?= Html::a('Logout', ['/admin/default/logout'], [
                                'class' => 'dropdown-item',
                                'data-method' => 'post',
                            ]) ?>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <?= $content ?>
        </div>
    </div>
</div>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
