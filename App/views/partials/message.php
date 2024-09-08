<?php use Framework\Session;

$success_message = Session::getFlashMessage('success_message');
$error_message = Session::getFlashMessage('error_message');

?>

<?php if (isset($success_message)): ?>
    <div class="text-center text-green-600 mb-4 p-4 bg-green-100 border border-green-400 rounded-lg shadow-md">
        <p class="font-semibold"><?= $success_message ?></p>
    </div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <div class="text-center text-red-600 mb-4 p-4 bg-red-100 border border-red-400 rounded-lg shadow-md">
        <p class="font-semibold"><?= $error_message ?></p>
    </div>
<?php endif; ?>