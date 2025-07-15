<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Product Catalog';
?>
<div class="container mt-4">
    <div class="row">
        <!-- Left: Category Filters -->
        <div class="col-md-3">
            <h5 class="mb-3">Filter by Category</h5>
            <form id="category-filter-form">
                <?php foreach ($categories as $category): ?>
                    <div class="form-check">
                    <input
                        class="form-check-input category-filter"
                        type="checkbox"
                        name="category[]"
                        value="<?= Html::encode($category->category_seourl) ?>"
                        id="cat_<?= $category->id ?>"
                        <?= in_array($category->category_seourl, $selectedCategories) ? 'checked' : '' ?>
                    >
                    <label class="form-check-label" for="cat_<?= $category->id ?>">
                        <?= Html::encode($category->categoryname) ?>
                    </label>
                    </div>
                <?php endforeach; ?>
            </form>
        </div>

        <!-- Right: Products -->
        <div class="col-md-9">
            <?php Pjax::begin(['id' => 'product-pjax', 'enablePushState' => false]); ?>
            <div class="row">
                <?php foreach ($dataProvider->getModels() as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 position-relative">
                            <!-- Wishlist Icon -->
                            <button type="button" class="btn btn-light position-absolute top-0 end-0 m-2 wishlist-btn" data-product-id="<?= $product->id ?>">
                                <i class="bi <?= in_array($product->id, $wishlistProductIds ?? []) ? 'bi-heart-fill text-danger' : 'bi-heart' ?>"></i>
                            </button>

                            <!-- Product Image -->
                            <?php if ($product->productimage): ?>
                                <img src="<?= Url::to("@web/uploads/{$product->productimage}") ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                            <?php else: ?>
                                <div class="card-img-top bg-light text-center py-5">No Image</div>
                            <?php endif; ?>

                            <!-- Product Info -->
                            <div class="card-body">
                                <h5 class="card-title"><?= Html::encode($product->productname) ?></h5>
                                <p class="card-text"><?= Html::encode($product->productdes) ?></p>
                                <p class="card-text text-muted">₹<?= number_format($product->productprice) ?></p>
                            </div>

                            <!-- Footer: Quantity + Add to Cart -->
                            <div class="card-footer d-flex flex-column align-items-start">
                                <div class="d-flex align-items-center gap-1 mb-2 quantity-control" 
                                     data-product-id="<?= $product->id ?>"
                                     data-min="<?= $product->min_quantity ?>"
                                     data-max="<?= $product->max_quantity ?>">
                                    <button class="btn btn-sm btn-outline-secondary btn-decrease px-2 py-0" type="button">−</button>
                                    <input 
                                        type="text" 
                                        class="form-control form-control-sm text-center quantity-input" 
                                        value="<?= $product->min_quantity ?>" 
                                        readonly 
                                        style="width: 40px;"
                                        min="<?= $product->min_quantity ?>" 
                                        max="<?= $product->max_quantity ?>"
                                    >
                                    <button class="btn btn-sm btn-outline-secondary btn-increase px-2 py-0" type="button">+</button>
                                </div>
                                <div class="d-flex justify-content-between w-100">
                                    <small class="text-muted">Category: <?= Html::encode($product->category->categoryname ?? 'N/A') ?></small>
                                    <button class="btn btn-sm btn-success add-to-cart-btn" data-product-id="<?= $product->id ?>">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if ($dataProvider->getCount() === 0): ?>
                    <div class="col-12">
                        <div class="alert alert-warning">No products found.</div>
                    </div>
                <?php endif; ?>
            </div>
            <?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$wishlistToggleUrl = Url::to(['/wishlist/toggle']);
$addToCartUrl = Url::to(['/cart/add']);

$this->registerJs(<<<JS
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

// Quantity increase/decrease with min/max limit
$(document).on('click', '.btn-increase, .btn-decrease', function () {
    const group = $(this).closest('.quantity-control');
    const input = group.find('.quantity-input');
    let quantity = parseInt(input.val());
    const min = parseInt(input.attr('min'));
    const max = parseInt(input.attr('max'));

    if ($(this).hasClass('btn-increase') && quantity < max) {
        quantity++;
    } else if ($(this).hasClass('btn-decrease') && quantity > min) {
        quantity--;
    }

    input.val(quantity);
});

// Add to cart
$(document).on('click', '.add-to-cart-btn', function () {
    const btn = $(this);
    const productId = btn.data('product-id');
    const quantity = btn.closest('.card').find('.quantity-input').val();
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.post({
        url: '{$addToCartUrl}',
        data: {
            id: productId,
            quantity: quantity,
            _csrf: csrfToken
        },
        success: function (response) {
            if (response.success) {
                alert('Product added to cart!');
                $('#cart-count').text(response.cartCount);
            } else {
                alert(response.message || 'Could not add to cart.');
            }
        },
        error: function () {
            alert('AJAX error while adding to cart.');
        }
    });
});

// Category filter
$(document).on('change', '.category-filter', function () {
    const selected = $('.category-filter:checked').map(function () {
        return this.value;
    }).get();

    const url = new URL(window.location.href);
    url.searchParams.delete('category[]');

    selected.forEach(function (id) {
        url.searchParams.append('category[]', id);
    });

    $.pjax.reload({
        container: '#product-pjax',
        url: url.toString(),
        replace: false
    });
});
JS,
\yii\web\View::POS_READY);
?>


