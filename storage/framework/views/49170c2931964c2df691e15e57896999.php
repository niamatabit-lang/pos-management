<?php $__env->startSection('title', __('app.change_password')); ?>

<?php $__env->startSection('content'); ?>

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title"><?php echo e(__('app.change_password')); ?></h1>
            <p class="page-subtitle"><?php echo e(__('app.change_password_note')); ?></p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div style="background:#d1f4df;color:#198754;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div style="background:#fde2e2;color:#dc3545;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="card" style="max-width:480px;">
        <form method="POST" action="<?php echo e(route('password.update')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label class="form-label"><?php echo e(__('app.current_password')); ?> <span class="required">*</span></label>
                <input type="password" name="current_password" class="form-control">
                <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label"><?php echo e(__('app.new_password')); ?> <span class="required">*</span></label>
                <input type="password" name="new_password" class="form-control">
                <small style="color:#888;"><?php echo e(__('app.min_6_chars')); ?></small>
                <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label"><?php echo e(__('app.confirm_new_password')); ?> <span class="required">*</span></label>
                <input type="password" name="new_password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary"><?php echo e(__('app.change_password_button')); ?></button>
        </form>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\pos-management\resources\views/auth/change-password.blade.php ENDPATH**/ ?>