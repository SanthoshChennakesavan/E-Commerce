<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Verify OTP';
?>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="text-center w-100" style="max-width: 420px;">
        <h3 class="mb-4"><?= Html::encode($this->title) ?></h3>

       <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
            <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endforeach; ?>

        <?php $form = ActiveForm::begin(); ?>

        <label for="otp" class="form-label fw-bold">Enter OTP</label>

        <div class="d-flex justify-content-center gap-3 mb-3">
            <?php for ($i = 0; $i < 4; $i++): ?>
                <input type="text"
                       class="otp-box text-center"
                       maxlength="1"
                       inputmode="numeric"
                       pattern="[0-9]*"
                       required>
            <?php endfor; ?>
            <input type="hidden" name="otp" id="otp-input">
        </div>

       <div class="mb-2 text-muted small">
            <span id="otp-timer">
                OTP expires in: <strong>--:--</strong>
            </span>
            <span id="otp-expired" class="text-danger d-none">
                OTP expired.
            </span>
        </div>

        <div class="form-group mt-3">
            <?= Html::submitButton('Verify', ['class' => 'btn btn-success px-4', 'id' => 'submit-btn']) ?>
        </div>

        <p class="mt-3 small">
            Didn't receive the OTP? <?= Html::a('Resend OTP', ['site/resend-otp', 'id' => $model->id]) ?>
        </p>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$otpExpirySeconds = $otpExpirySeconds ?? 0;
$otpIsExpired = $otpIsExpired ?? false;

$js = <<<JS
let timer = $otpExpirySeconds;
let interval;
let timerDisplay = document.getElementById('otp-timer')?.querySelector('strong');
let timerBox = document.getElementById('otp-timer');
let expiredMsg = document.getElementById('otp-expired');
let submitBtn = document.getElementById('submit-btn');

function updateTimer() {
    if (timer <= 0) {
        timerDisplay.innerText = '00:00';
        timerBox.classList.add('d-none');
        expiredMsg.classList.remove('d-none');
        submitBtn.disabled = true;
        clearInterval(interval);
        return;
    }

    let minutes = Math.floor(timer / 60).toString().padStart(2, '0');
    let seconds = (timer % 60).toString().padStart(2, '0');
    timerDisplay.innerText = minutes + ':' + seconds;
    timer--;
}


if (timer > 0) {
    interval = setInterval(updateTimer, 1000);
    updateTimer();
}


document.querySelector('form').addEventListener('submit', () => {
    clearInterval(interval);
});


const boxes = document.querySelectorAll('.otp-box');
const hiddenInput = document.getElementById('otp-input');

boxes.forEach((box, idx) => {
    box.addEventListener('input', () => {
        box.value = box.value.replace(/[^0-9]/g, '').charAt(0);
        if (box.value && idx < boxes.length - 1) boxes[idx + 1].focus();
        collectOtp();
    });

    box.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !box.value && idx > 0) {
            boxes[idx - 1].focus();
        }
    });
});

function collectOtp() {
    hiddenInput.value = Array.from(boxes).map(b => b.value).join('');
}
JS;

$this->registerJs($js);
$this->registerCss(<<<CSS
.otp-box {
    width: 60px;
    height: 60px;
    font-size: 24px;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin: 0 4px;
}
@media (max-width: 576px) {
    .otp-box {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }
}
CSS);
?>
