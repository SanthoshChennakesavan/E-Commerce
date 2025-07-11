<?php

use yii\helpers\Html;

/** @var array $pieLabels */
/** @var array $pieData */
/** @var array $barLabels */
/** @var array $barData */

$this->title = 'Admin Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container mt-4">

<?php foreach (['success', 'error', 'info', 'warning'] as $type): ?>
    <?php if (Yii::$app->session->hasFlash($type)): ?>
        <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash($type) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-3 shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Categories</h5>
                    <p class="card-text fs-2"><?= count($pieLabels) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-success mb-3 shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text fs-2"><?= array_sum($barData) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <strong>Category (Pie Chart)</strong>
                </div>
                <div class="card-body">
                    <canvas id="pieChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <strong>Products (Bar Chart)</strong>
                </div>
                <div class="card-body">
                    <canvas id="barChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js">
    
</script>
<script>
    const pieLabels = <?= json_encode($pieLabels) ?>;
    const pieData = <?= json_encode($pieData) ?>;

    const barLabels = <?= json_encode($barLabels) ?>;
    const barData = <?= json_encode($barData) ?>;

    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieData,
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6610f2'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: barLabels,
            datasets: [{
                label: 'Product Count',
                data: barData,
                backgroundColor: '#17a2b8',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>