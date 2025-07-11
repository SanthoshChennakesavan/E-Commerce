<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\BootstrapPluginAsset;

BootstrapAsset::register($this);
BootstrapPluginAsset::register($this);
$this->beginPage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->head() ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= Html::csrfMetaTags() ?>
    <title>Admin Panel</title>

    <link href="<?= Yii::getAlias('@web') ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php $this->beginBody() ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= \yii\helpers\Url::to(['/admin/default/dashboard']) ?>">
            <img src="<?= Yii::getAlias('@web') ?>/images/amazonlogo.png" alt="Logo" width="40" height="40">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav"> 
            <ul class="navbar-nav mx-auto ">
                <li class="nav-item">
                    <?= Html::a('Dashboard', ['/admin/default/dashboard'], ['class' => 'nav-link text-white']) ?>
                </li>
                <div>
                    <ul class="navbar-nav ms-lg-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link text-white" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">User Master</a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li>
                                    <?= Html::a('Admin', ['/admin/adminuser/admin-index'], [
                                        'class' => 'dropdown-item',
                                        'data-method' => 'post',
                                    ]) ?>
                                    <?= Html::a('User', ['/admin/user/user-index'], [
                                        'class' => 'dropdown-item',
                                        'data-method' => 'post'
                                    ]) ?>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <li class="nav-item">
                 <?= Html::a('Category', ['/admin/category/category'], ['class' => 'nav-link text-white']) ?>
                </li>
                <li class="nav-item">
                    <?= Html::a('Product', ['/admin/product/product'], ['class' => 'nav-link text-white']) ?>
                </li>
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

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
