<?php $__env->startSection('title', __('app.edit_user')); ?>

<?php $__env->startSection('content'); ?>

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title"><?php echo e($user->name); ?> — <?php echo e(__('app.edit')); ?></h1>
            <p class="page-subtitle"><?php echo e(__('app.leave_password_blank_note')); ?></p>
        </div>

        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">&larr; <?php echo e(__('app.back')); ?></a>
    </div>

    <div class="card">
        <form method="POST" action="<?php echo e(route('users.update', $user)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('app.name')); ?> <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label"><?php echo e(__('app.email')); ?> <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('app.new_password_optional')); ?></label>
                    <input type="password" name="password" class="form-control">
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label"><?php echo e(__('app.confirm_password')); ?></label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="is_active" value="1" <?php if($user->is_active): echo 'checked'; endif; ?>>
                    <?php echo e(__('app.id_will_stay_active')); ?>

                </label>
            </div>

            <?php if($mode === 'shop_owner'): ?>

                <div class="form-group">
                    <label class="form-label"><?php echo e(__('app.shops_to_grant_access')); ?></label>
                    <div style="display:flex;flex-wrap:wrap;gap:16px;">
                        <?php $__empty_1 = true; $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <label class="form-check">
                                <input type="checkbox" name="shop_ids[]" value="<?php echo e($shop->id); ?>" <?php if(in_array($shop->id, $assignedShopIds)): echo 'checked'; endif; ?>>
                                <?php echo e($shop->name); ?>

                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <span style="color:#999;"><?php echo e(__('app.no_shops_found')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else: ?>

                <div class="form-group">
                    <label class="form-label"><?php echo e(__('app.shop')); ?></label>
                    <select name="shop_id" class="form-select">
                        <?php $__empty_1 = true; $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($shop->id); ?>" <?php if($currentShopId === $shop->id): echo 'selected'; endif; ?>><?php echo e($shop->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <option value="" disabled><?php echo e(__('app.no_shop_access')); ?></option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label"><?php echo e(__('app.permissions_checkbox_label')); ?></label>
                    <div style="display:flex;flex-wrap:wrap;gap:16px;">
                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="form-check">
                                <input type="checkbox" name="permissions[]" value="<?php echo e($key); ?>" <?php if(in_array($key, $currentPermissions)): echo 'checked'; endif; ?>>
                                <?php echo e(__('app.permission_' . $key)); ?>

                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

            <?php endif; ?>

            <button type="submit" class="btn btn-primary"><?php echo e(__('app.update')); ?></button>
        </form>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\pos-management\resources\views/users/edit.blade.php ENDPATH**/ ?>