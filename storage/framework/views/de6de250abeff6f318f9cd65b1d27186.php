<?php $__env->startSection('title', __('app.nav_activity_logs')); ?>

<?php $__env->startSection('content'); ?>

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title"><?php echo e(__('app.nav_activity_logs')); ?></h1>
            <p class="page-subtitle"><?php echo e(__('app.activity_logs_subtitle')); ?></p>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo e(__('app.time')); ?></th>
                    <th><?php echo e(__('app.user')); ?></th>
                    <th><?php echo e(__('app.action')); ?></th>
                    <th><?php echo e(__('app.description')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($log->created_at->format('d M Y, h:i A')); ?></td>
                        <td><?php echo e($log->user->name ?? __('app.unknown')); ?></td>
                        <td><?php echo e($log->action); ?></td>
                        <td><?php echo e($log->description); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="table-empty"><?php echo e(__('app.no_activity_logs')); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="table-footer">
            <div>
                <?php echo e(__('app.showing_results', ['from' => $logs->firstItem() ?? 0, 'to' => $logs->lastItem() ?? 0, 'total' => $logs->total()])); ?>

            </div>

            <?php echo e($logs->links('vendor.pagination.custom')); ?>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\pos-management\resources\views/activity-logs/index.blade.php ENDPATH**/ ?>