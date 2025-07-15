<?php
use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap5\BootstrapAsset;
use app\components\assets\AppAsset;

AppAsset::register($this);
BootstrapAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="<?= Yii::getAlias('@web') ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php $this->beginBody() ?>

<div class="container mt-4">
    <?= $content ?>
</div>

<script src="<?= Yii::getAlias('@web') ?>/bootstrap/js/bootstrap.bundle.min.js"></script> 
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>  
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
