<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Grant / Revoke Permissions';
$granted = json_decode($model->rbac, true);
if (!is_array($granted)) {
    $granted = [];
}

// echo "<pre>";
// print_r($granted);
// echo "</pre>";
?>

<!-- <style>
    table.permission-table {
        border-collapse: collapse;
        width: 100%;
        max-width: 900px;
    }
    table.permission-table th,
    table.permission-table td {
        border: 1px solid #ccc;
        text-align: center;
        padding: 15px;
    }
    label.permission-label {
        margin: 0 10px;
        font-weight: normal;
    }
    input[type="checkbox"] {
        width: 18px;
        height: 18px;
        vertical-align: middle;
    }
</style> -->

<h4 class="text-center mb-4">Grant / Revoke Permissions for: <?= Html::encode($model->username) ?></h4>

<div class="d-flex justify-content-center">
    <form action="" method="post">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

        <table class="permission-table">
            <thead class="table-light">
                <tr>
                    <th>Module</th>
                    <th>Main Access</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dashboard -->
                <tr>
                    <td>Dashboard</td>
                    <td>
                        <input type="checkbox" name="rbac[]" value="access_dashboard" <?= in_array('access_dashboard', $granted) ? 'checked' : '' ?>>
                    </td>
                    <td class="text-muted">—</td>
                </tr>

                <!-- Admin -->
                <tr>
                    <td>Admin</td>
                    <td>
                        <input type="checkbox" name="rbac[]" value="access_admin" <?= in_array('access_admin', $granted) ? 'checked' : '' ?>>
                    </td>
                    <td>
                        <label class="permission-label">
                            <input type="checkbox" name="rbac[]" value="create_admin" <?= in_array('create_admin', $granted) ? 'checked' : '' ?>> Create
                        </label>
                        <label class="permission-label">
                            <input type="checkbox" name="rbac[]" value="update_admin" <?= in_array('update_admin', $granted) ? 'checked' : '' ?>> Update
                        </label>
                        <label class="permission-label">
                            <input type="checkbox" name="rbac[]" value="delete_admin" <?= in_array('delete_admin', $granted) ? 'checked' : '' ?>> Delete
                        </label>
                    </td>
                </tr>

                <!-- Customer -->
                <tr>
                    <td>User</td>
                    <td>
                        <input type="checkbox" name="rbac[]" value="access_user" <?= in_array('access_user', $granted) ? 'checked' : '' ?>>
                    </td>
                    <td>
                        <label class="permission-label">
                            <input type="checkbox" name="rbac[]" value="create_user" <?= in_array('create_user', $granted) ? 'checked' : '' ?>> Create
                        </label>
                        <label class="permission-label">
                            <input type="checkbox" name="rbac[]" value="update_user" <?= in_array('update_user', $granted) ? 'checked' : '' ?>> Update
                        </label>
                        <label class="permission-label">
                            <input type="checkbox" name="rbac[]" value="delete_user" <?= in_array('delete_user', $granted) ? 'checked' : '' ?>> Delete
                        </label>
                    </td>
                </tr>

                <!-- Category -->
                <tr>
                    <td>Category</td>
                    <td>
                        <input type="checkbox" name="rbac[]" value="access_category" <?= in_array('access_category', $granted) ? 'checked' : '' ?>>
                    </td>
                    <td>
                        <label class="permission-label">
                            <input type="checkbox" name="rbac[]" value="create_category" <?= in_array('create_category', $granted) ? 'checked' : '' ?>> Create
                        </label>
                        <label class="permission-label">
                            <input type="checkbox" name="rbac[]" value="update_category" <?= in_array('update_category', $granted) ? 'checked' : '' ?>> Update
                        </label>
                        <label class="permission-label">
                            <input type="checkbox" name="rbac[]" value="delete_category" <?= in_array('delete_category', $granted) ? 'checked' : '' ?>> Delete
                        </label>
                    </td>
                </tr>

                <!-- Product -->
                <tr>
                    <td>Product</td>
                    <td>
                        <input type="checkbox" name="rbac[]" value="access_product" <?= in_array('access_product', $granted) ? 'checked' : '' ?>>
                    </td>
                    <td>
                        <label class="permission-label">
                            <input type="checkbox" name="rbac[]" value="create_product" <?= in_array('create_product', $granted) ? 'checked' : '' ?>> Create
                        </label>
                        <label class="permission-label">
                            <input type="checkbox" name="rbac[]" value="update_product" <?= in_array('update_product', $granted) ? 'checked' : '' ?>> Update
                        </label>
                        <label class="permission-label">
                            <input type="checkbox" name="rbac[]" value="delete_product" <?= in_array('delete_product', $granted) ? 'checked' : '' ?>> Delete
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-4" style="max-width: 900px; margin: 0 auto;">
            <button type="submit" class='btn btn-primary'>Submit</button>
            <a href="<?= Url::to(['admin-index']) ?>" class='btn btn-secondary'>Back</a>
        </div>
    </form>
</div>