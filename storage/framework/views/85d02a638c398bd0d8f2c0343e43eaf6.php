<?php
$flashType = '';
$flashMessage = '';
$bgColor = '';
$textColor = 'white';

// Check for validation errors
if ($errors->any()) {
    $flashType = 'error';
    $flashMessage = $errors->first();
} elseif (session()->has('success')) {
    $flashType = 'success';
    $flashMessage = session('success');
} elseif (session()->has('error')) {
    $flashType = 'error';
    $flashMessage = session('error');
} elseif (session()->has('warning')) {
    $flashType = 'warning';
    $flashMessage = session('warning');
} elseif (session()->has('info')) {
    $flashType = 'info';
    $flashMessage = session('info');
}

// Set background color based on type
switch ($flashType) {
    case 'success':
        $bgColor = '#10B981';
        break;
    case 'error':
        $bgColor = '#EF4444';
        break;
    case 'warning':
        $bgColor = '#F59E0B';
        break;
    case 'info':
        $bgColor = '#3B82F6';
        break;
    default:
        $bgColor = '#3B82F6';
}
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flashMessage): ?>
<div style="
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 99999;
    padding: 20px 30px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    font-size: 16px;
    font-weight: bold;
    background-color: <?php echo e($bgColor); ?>;
    color: <?php echo e($textColor); ?>;
">
    <?php echo e($flashMessage); ?>

    <button onclick="this.parentElement.remove()" style="
        margin-left: 15px;
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 20px;
    ">×</button>
</div>
<script>
    // Auto-remove after 5 seconds
    setTimeout(function() {
        var flash = document.querySelector('[style*="position: fixed"][style*="top: 80px"]');
        if (flash) flash.remove();
    }, 5000);
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php /**PATH C:\Users\enter\Herd\bookshop\resources\views/components/flash-message.blade.php ENDPATH**/ ?>