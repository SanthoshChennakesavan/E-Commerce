<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var \app\models\Cart[] $cartItems */
/** @var array $wishlistProductIds */

$this->title = 'My Cart';

// Calculate subtotal
$subtotal = 0;
foreach ($cartItems as $cart) {
    $subtotal += $cart->product->productprice * $cart->quantity;
}
?>


<div class="container mt-4">
    <h2 class="mb-4"><?= Html::encode($this->title) ?></h2>

    <?php if (count($cartItems) > 0): ?>
        <div class="row">
            <!-- LEFT: Cart items -->
            <div class="col-md-8">
                <?php foreach ($cartItems as $cart):
                    $product = $cart->product;
                ?>
                    <div class="card p-3 mb-3 position-relative shadow-sm">
                        <!-- Heart icon -->
                        <button type="button" class="btn btn-light position-absolute top-0 end-0 m-2 wishlist-btn" data-product-id="<?= $product->id ?>">
                            <i class="bi <?= in_array($product->id, $wishlistProductIds ?? []) ? 'bi-heart-fill text-danger' : 'bi-heart' ?>"></i>
                        </button>

                        <div class="row align-items-center">
                            <!-- Image -->
                            <div class="col-md-3">
                                <?php if ($product->productimage): ?>
                                    <img src="<?= Url::to("@web/uploads/{$product->productimage}") ?>" class="img-fluid rounded" alt="Product Image">
                                <?php else: ?>
                                    <div class="bg-light text-center py-4">No Image</div>
                                <?php endif; ?>
                            </div>

                            <!-- Details -->
                            <div class="col-md-6">
                                <h5 class="mb-1"><?= Html::encode($product->productname) ?></h5>
                                <small class="text-muted"><?= Html::encode($product->productdes) ?></small><br>
                                <span class="text-muted">Category: <?= Html::encode($product->category->categoryname ?? 'N/A') ?></span>
                                <p class="mt-2 mb-0">
                                    Price: <strong>₹<?= number_format($product->productprice) ?></strong><br>
                                    Quantity: <?= $cart->quantity ?>
                                </p>
                            </div>

                            <!-- Price and Remove -->
                            <div class="col-md-3 text-end">
                                <div class="mb-2 text-success fw-bold">₹<?= number_format($product->productprice * $cart->quantity) ?></div>
                                <button class="btn btn-danger remove-from-cart-btn" data-id="<?= $cart->id ?>">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- RIGHT: Summary -->
            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h5 class="mb-3">Cart Summary</h5>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $cart): ?>
                                <tr>
                                    <td><?= Html::encode($cart->product->productname) ?></td>
                                    <td><?= $cart->quantity ?></td>
                                    <td>₹<?= number_format($cart->product->productprice) ?></td>
                                    <td>₹<?= number_format($cart->product->productprice * $cart->quantity) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Subtotal:</th>
                                <th>₹<?= number_format($subtotal) ?></th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end">Grand Total:</th>
                                <th>₹<?= number_format($subtotal) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="d-grid">
                        <a href="<?= Url::to(['/checkout']) ?>" class="btn btn-success btn-block mt-2">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Your cart is empty.</div>
    <?php endif; ?>
</div>

<?php
$removeUrl = Url::to(['/cart/remove']);
$wishlistToggleUrl = Url::to(['/wishlist/toggle']);

$this->registerJs(<<<JS
// Remove from cart
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

// Wishlist toggle
$(document).on('click', '.wishlist-btn', function () {
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
            if (response.success) {
                icon.toggleClass('bi-heart bi-heart-fill text-danger');
            } else {
                alert(response.message || 'Error updating wishlist.');
            }
        },
        error: function() {
            alert('AJAX error: Could not contact wishlist/toggle');
        }
    });
});
JS);
?>
