<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\BootstrapPluginAsset;

\yii\web\JqueryAsset::register($this);
BootstrapAsset::register($this);
BootstrapPluginAsset::register($this);

$this->registerCssFile(Url::to('@web/bootstrap/css/bootstrap.min.css'));
$this->registerCssFile(Url::to('@web/bootstrap/bootstrap-icons/bootstrap-icons.css'));
$this->registerJsFile(Url::to('@web/bootstrap/js/bootstrap.bundle.min.js'), ['depends' => [\yii\web\JqueryAsset::class]]);

$this->beginPage();

$isGuest = Yii::$app->user->isGuest;
$username = !$isGuest ? Yii::$app->user->identity->username : null;

// ✅ Cart Count for logged-in users
$cartCount = 0;
if (!$isGuest) {
    $cartCount = \app\models\Cart::find()
        ->where(['user_id' => Yii::$app->user->id])
        ->count();
}
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head(); ?>
     <link href="<?= Yii::getAlias('@web') ?>/bootstrap/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
<?php $this->beginBody() ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        <a class="navbar-brand" href="<?= Url::to(['/site/index']) ?>">
            <img src="<?= Yii::getAlias('@web') ?>/images/amazonlogo.png" alt="Logo" width="40" height="40">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <?= Html::a('Home', ['/site/index'], ['class' => 'nav-link text-white']) ?>
                </li>

                <li class="nav-item">
                    <?= Html::a('Wishlist', ['/wishlist/index'], ['class' => 'nav-link text-white']) ?>
                </li>

                <li class="nav-item">
                    <?= Html::a(
                        'Cart <span class="badge bg-warning text-dark">' . $cartCount . '</span>',
                        ['/cart/cartindex'],
                        ['class' => 'nav-link text-white', 'encode' => false] // Important: encode => false to allow HTML
                    ) ?>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        <?= $isGuest ? 'Hello User' : 'Hello ' . Html::encode($username) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php if ($isGuest): ?>
                            <li><span class="dropdown-item text-muted">Are you sure to login/signup?</span></li>
                            <li><a class="dropdown-item" href="<?= Url::to(['site/login']) ?>">Login</a></li>
                            <li><a class="dropdown-item" href="<?= Url::to(['site/signup']) ?>">Signup</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item text-danger" href="<?= Url::to(['site/logout']) ?>" data-method="post">Logout</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <?= $content ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
