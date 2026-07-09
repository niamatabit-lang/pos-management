<?php $__env->startSection('title', __('app.users')); ?>

<?php $__env->startSection('content'); ?>

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">
                <?php if(auth()->user()->isSuperAdmin()): ?>
                    <?php echo e(__('app.shop_owner_list')); ?>

                <?php else: ?>
                    <?php echo e(__('app.manager_employee_list')); ?>

                <?php endif; ?>
            </h1>
            <p class="page-subtitle">
                <?php if(auth()->user()->isSuperAdmin()): ?>
                    <?php echo e(__('app.all_shop_owners_note')); ?>

                <?php else: ?>
                    <?php echo e(__('app.your_created_ids_note')); ?>

                <?php endif; ?>
            </p>
        </div>

        <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
            <?php if(auth()->user()->isSuperAdmin()): ?>
                + <?php echo e(__('app.new_shop_owner')); ?>

            <?php else: ?>
                + <?php echo e(__('app.new_manager_employee')); ?>

            <?php endif; ?>
        </a>
    </div>

    <?php if(session('success')): ?>
        <div style="background:#d1f4df;color:#198754;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo e(__('app.name')); ?></th>
                    <th><?php echo e(__('app.email')); ?></th>
                    <?php if(!auth()->user()->isSuperAdmin()): ?>
                        <th><?php echo e(__('app.role')); ?></th>
                    <?php endif; ?>
                    <th><?php echo e(__('app.shop')); ?></th>
                    <th><?php echo e(__('app.status')); ?></th>
                    <th class="text-right"><?php echo e(__('app.action')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($user->name); ?></td>
                        <td><?php echo e($user->email); ?></td>
                        <?php if(!auth()->user()->isSuperAdmin()): ?>
                            <td>
                                <?php if($user->isManager()): ?>
                                    <span class="badge badge-warning"><?php echo e(__('app.role_manager')); ?></span>
                                <?php else: ?>
                                    <span class="badge badge-success"><?php echo e(__('app.role_employee')); ?></span>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        <td><?php echo e($user->shops->pluck('name')->join(', ') ?: '-'); ?></td>
                        <td>
                            <?php if($user->is_active): ?>
                                <span class="badge badge-success"><?php echo e(__('app.active')); ?></span>
                            <?php else: ?>
                                <span class="badge badge-danger"><?php echo e(__('app.inactive')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-secondary btn-sm"><?php echo e(__('app.edit')); ?></a>
                            <?php if($user->is_active): ?>
                                <form method="POST" action="<?php echo e(route('users.destroy', $user)); ?>" style="display:inline;" onsubmit="return confirm('<?php echo e(__('app.confirm_deactivate')); ?>');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm"><?php echo e(__('app.deactivate')); ?></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="<?php echo e(auth()->user()->isSuperAdmin() ? 5 : 6); ?>" class="table-empty"><?php echo e(__('app.no_ids_created_yet')); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\pos-management\resources\views/users/index.blade.php ENDPATH**/ ?>