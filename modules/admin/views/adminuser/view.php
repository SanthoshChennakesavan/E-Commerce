<?php
use yii\helpers\Html;

$this->title = 'Grant / Revoke Permissions';
$granted = json_decode($model->rbac, true);
if (!is_array($granted)) {
    $granted = [];
}
?>
<style>
    table, thead, tbody, tr, td {
        border: 1px solid black;
        text-align: center;
        padding: 25px;
    }
    input[type="checkbox"] {
        height: 20px;
        width: 20px;
        margin: 0 5px;
}
</style>

<h4 class="text-center">Grant / Revoke Permissions for: <?= Html::encode($model->username) ?></h4>

<div class="d-flex justify-content-center align-content-center flex-row">
    <form action="" method="post">

        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

        <table>
            <thead>
                <tr>
                    <td>Access Permission</td>
                    <td>Main Menu</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Dashboard</td>
                    <td><input type="checkbox" name="rbac[]" value="access_dashboard" <?= in_array('access_dashboard', $granted) ? 'checked' : '' ?>></td>
                    <td>
                        <input type="checkbox" name="rbac[]" value="create_dashboard" <?= in_array('create_dashboard', $granted) ? 'checked' : '' ?>>
                        <input type="checkbox" name="rbac[]" value="update_dashboard" <?= in_array('update_dashboard', $granted) ? 'checked' : '' ?>>
                        <input type="checkbox" name="rbac[]" value="delete_dashboard" <?= in_array('delete_dashboard', $granted) ? 'checked' : '' ?>>
                    </td>
                </tr>
                <tr>
                    <td>Admin</td>
                    <td><input type="checkbox" name="rbac[]" value="access_admin" <?= in_array('access_admin', $granted) ? 'checked' : '' ?>></td>
                    <td>
                        <input type="checkbox" name="rbac[]" value="create_admin" <?= in_array('create_admin', $granted) ? 'checked' : '' ?>>
                        <input type="checkbox" name="rbac[]" value="update_admin" <?= in_array('update_admin', $granted) ? 'checked' : '' ?>>
                        <input type="checkbox" name="rbac[]" value="delete_admin" <?= in_array('delete_admin', $granted) ? 'checked' : '' ?>>
                    </td>
                </tr>
                <tr>
                    <td>Customer</td>
                    <td><input type="checkbox" name="rbac[]" value="access_customer" <?= in_array('access_customer', $granted) ? 'checked' : '' ?>></td>
                    <td>
                        <input type="checkbox" name="rbac[]" value="create_customer" <?= in_array('create_customer', $granted) ? 'checked' : '' ?>>
                        <input type="checkbox" name="rbac[]" value="update_customer" <?= in_array('update_customer', $granted) ? 'checked' : '' ?>>
                        <input type="checkbox" name="rbac[]" value="delete_customer" <?= in_array('delete_customer', $granted) ? 'checked' : '' ?>>
                    </td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td><input type="checkbox" name="rbac[]" value="access_category" <?= in_array('access_category', $granted) ? 'checked' : '' ?>></td>
                    <td>
                        <input type="checkbox" name="rbac[]" value="create_category" <?= in_array('create_category', $granted) ? 'checked' : '' ?>>
                        <input type="checkbox" name="rbac[]" value="update_category" <?= in_array('update_category', $granted) ? 'checked' : '' ?>>
                        <input type="checkbox" name="rbac[]" value="delete_category" <?= in_array('delete_category', $granted) ? 'checked' : '' ?>>
                    </td>
                </tr>
                <tr>
                    <td>Product</td>
                    <td><input type="checkbox" name="rbac[]" value="access_product" <?= in_array('access_product', $granted) ? 'checked' : '' ?>></td>
                    <td>
                        <input type="checkbox" name="rbac[]" value="create_product" <?= in_array('create_product', $granted) ? 'checked' : '' ?>>
                        <input type="checkbox" name="rbac[]" value="update_product" <?= in_array('update_product', $granted) ? 'checked' : '' ?>>
                        <input type="checkbox" name="rbac[]" value="delete_product" <?= in_array('delete_product', $granted) ? 'checked' : '' ?>>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-3">
            <button type="submit" class='btn btn-primary'>Submit</button>
            <a href="admin-index" class='btn btn-secondary'>Back</a>
        </div>
    </form>
</div>


