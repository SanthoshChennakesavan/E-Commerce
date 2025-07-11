<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\models\Wishlist[] $wishlistProducts */
$this->title = 'My Wishlist';
?>

<div class="container mt-4">
    <h2><?= Html::encode($this->title) ?></h2>

    <div class="row">
        <?php foreach ($wishlistProducts as $wishlist): 
            $product = $wishlist->product;
        ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 position-relative">

                    <button class="btn btn-light position-absolute top-0 end-0 m-2 wishlist-btn" data-product-id="<?= $product->id ?>">
                        <i class="bi bi-heart-fill text-danger"></i>
                    </button>

                    <?php if ($product->productimage): ?>
                        <img src="<?= Url::to("@web/uploads/{$product->productimage}") ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                    <?php else: ?>
                        <div class="card-img-top bg-light text-center py-5">No Image</div>
                    <?php endif; ?>

                    <div class="card-body">
                        <h5 class="card-title"><?= Html::encode($product->productname) ?></h5>
                        <p class="card-text"><?= Html::encode($product->productdes) ?></p>
                        <p class="card-text text-muted">₹<?= number_format($product->productprice) ?></p>
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <small class="text-muted">Category: <?= Html::encode($product->category->categoryname ?? 'N/A') ?></small>
                        <button class="btn btn-sm btn-success add-to-cart-btn" data-product-id="<?= $product->id ?>">
                            <i class="bi bi-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (count($wishlistProducts) === 0): ?>
            <div class="col-12">
                <div class="alert alert-warning">Your wishlist is empty.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php

$wishlistToggleUrl = Url::to(['/wishlist/toggle']);


$this->registerJs(<<<JS
$(document).on('click', '.wishlist-btn', function() {
    const btn = $(this);
    const icon = btn.find('i');
    const productId = btn.data('product-id');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.post({
        url: '{$wishlistToggleUrl}',
        data: {
            id: productId,
            _csrf: csrfToken
        },
        success: function(response) {
            if (response.success && response.status === 'removed') {
                btn.closest('.col-md-4').fadeOut(() => {
                    btn.closest('.col-md-4').remove();
                });
            }
        },
        error: function() {
            alert('Error contacting the server.');
        }
    });
});
JS);
?>
