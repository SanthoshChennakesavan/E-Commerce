<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var \app\models\Cart[] $cartItems */

$this->title = 'My Cart';
?>
<div class="container mt-4">
    <h2><?= Html::encode($this->title) ?></h2>

    <?php if (count($cartItems) > 0): ?>
        <div class="row">
            <?php foreach ($cartItems as $cart): 
                $product = $cart->product;
            ?>
                <div class="col-md-12 mb-3">
                    <div class="card p-3">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <?php if ($product->productimage): ?>
                                    <img src="<?= Url::to("@web/uploads/{$product->productimage}") ?>" class="img-fluid">
                                <?php else: ?>
                                    <div class="bg-light text-center py-4">No Image</div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <h5><?= Html::encode($product->productname) ?></h5>
                                <p><?= Html::encode($product->productdes) ?></p>
                                <p>₹<?= number_format($product->productprice) ?> x <?= $cart->quantity ?></p>
                            </div>
                            <div class="col-md-2">
                                <strong>₹<?= number_format($product->productprice * $cart->quantity) ?></strong>
                            </div>
                            <div class="col-md-2 text-end">
                                <button class="btn btn-danger remove-from-cart-btn" data-id="<?= $cart->id ?>">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Your cart is empty.</div>
    <?php endif; ?>
</div>

<?php
$removeUrl = Url::to(['/cart/remove']);
$this->registerJs(<<<JS
$(document).on('click', '.remove-from-cart-btn', function () {
    const cartId = $(this).data('id');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (confirm('Are you sure you want to remove this item?')) {
        $.post({
            url: '{$removeUrl}',
            data: { id: cartId, _csrf: csrfToken },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Failed to remove from cart.');
                }
            }
        });
    }
});
JS);
?>
