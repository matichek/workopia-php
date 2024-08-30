<?php if (isset($_SESSION['success_message'])): ?>
    <div class="text-center text-green-600 mb-4 p-4 bg-green-100 border border-green-400 rounded-lg shadow-md">
        <p class="font-semibold"><?= $_SESSION['success_message'] ?></p>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="text-center text-red-600 mb-4 p-4 bg-red-100 border border-red-400 rounded-lg shadow-md">
        <p class="font-semibold"><?= $_SESSION['error_message'] ?></p>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>