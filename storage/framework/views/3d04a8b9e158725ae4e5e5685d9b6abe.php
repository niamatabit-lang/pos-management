<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(__('app.login')); ?> - POS Management</title>

    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/forms.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/buttons.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/utilities.css')); ?>">
</head>
<body style="background:var(--bg, #f4f6f9);min-height:100vh;display:flex;align-items:center;justify-content:center;">

    <div class="card" style="width:100%;max-width:400px;position:relative;">

        <form method="POST" action="<?php echo e(route('locale.switch', app()->getLocale() === 'bn' ? 'en' : 'bn')); ?>" style="position:absolute;top:16px;right:16px;">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-secondary btn-sm">
                <?php if(app()->getLocale() === 'bn'): ?> English <?php else: ?> বাংলা <?php endif; ?>
            </button>
        </form>

        <div style="text-align:center;margin-bottom:24px;">
            <div style="font-size:22px;font-weight:700;color:#198754;">POS Management</div>
            <p style="color:#888;margin-top:6px;"><?php echo e(__('app.login_to_account')); ?></p>
        </div>

        <?php if(session('error')): ?>
            <div style="background:#fde2e2;color:#dc3545;padding:12px 16px;border-radius:10px;margin-bottom:18px;font-weight:600;">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div style="background:#fde2e2;color:#dc3545;padding:12px 16px;border-radius:10px;margin-bottom:18px;font-weight:600;">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label class="form-label"><?php echo e(__('app.email')); ?></label>
                <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" autofocus required>
            </div>

            <div class="form-group">
                <label class="form-label"><?php echo e(__('app.password')); ?></label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-check" style="margin-bottom:18px;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember" class="form-label" style="margin:0;"><?php echo e(__('app.remember_me')); ?></label>
                </div>
                <a href="<?php echo e(route('password.request')); ?>" style="font-size:13px;color:#198754;"><?php echo e(__('app.forgot_password')); ?></a>
            </div>

            <button type="submit" class="btn btn-primary btn-block"><?php echo e(__('app.login_button')); ?></button>
        </form>

    </div>

</body>
</html>
<?php /**PATH D:\pos-management\resources\views/auth/login.blade.php ENDPATH**/ ?>