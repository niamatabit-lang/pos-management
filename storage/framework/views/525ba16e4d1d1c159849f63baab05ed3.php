<?php $__env->startSection('title', __('app.nav_stock')); ?>

<?php $__env->startSection('content'); ?>

<div class="page">

    <div class="page-header">

        <div>
            <h1 class="page-title">
                <?php echo e(__('app.nav_stock')); ?>

            </h1>

            <p class="page-subtitle">
                <?php echo e(__('app.stock_page_subtitle')); ?>

            </p>
        </div>

        <a href="<?php echo e(route('stock.create')); ?>" class="btn btn-primary">
            + <?php echo e(__('app.stock_adjustment')); ?>

        </a>

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

    <div class="card" style="margin-bottom:20px;">
        <form method="GET" action="<?php echo e(route('stock.index')); ?>" class="form-row-3">

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label"><?php echo e(__('app.product')); ?></label>
                <select name="product_id" class="form-select">
                    <option value=""><?php echo e(__('app.all_products')); ?></option>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($product->id); ?>" <?php if(request('product_id') == $product->id): echo 'selected'; endif; ?>>
                            <?php echo e($product->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label"><?php echo e(__('app.type')); ?></label>
                <select name="type" class="form-select">
                    <option value=""><?php echo e(__('app.all_types')); ?></option>
                    <option value="out" <?php if(request('type') == 'out'): echo 'selected'; endif; ?>><?php echo e(__('app.stock_decreased')); ?></option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;display:flex;align-items:flex-end;gap:10px;">
                <button type="submit" class="btn btn-secondary"><?php echo e(__('app.filter')); ?></button>
                <a href="<?php echo e(route('stock.index')); ?>" class="btn btn-secondary"><?php echo e(__('app.reset')); ?></a>
            </div>

        </form>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo e(__('app.date')); ?></th>
                    <th><?php echo e(__('app.product')); ?></th>
                    <th><?php echo e(__('app.type')); ?></th>
                    <th class="text-right"><?php echo e(__('app.quantity')); ?></th>
                    <th><?php echo e(__('app.note')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($movement->created_at->format('d M Y, h:i A')); ?></td>
                        <td><?php echo e($movement->product->name ?? 'N/A'); ?></td>
                        <td>
                            <?php if($movement->type === 'in'): ?>
                                <span class="badge badge-success"><?php echo e(__('app.stock_in')); ?></span>
                            <?php else: ?>
                                <span class="badge badge-danger"><?php echo e(__('app.stock_out')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right"><?php echo e(number_format($movement->quantity)); ?></td>
                        <td><?php echo e($movement->note ?? '-'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="table-empty">
                            <?php echo e(__('app.no_stock_movements')); ?>

                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="table-footer">
            <div>
                <?php echo e(__('app.showing_results', ['from' => $movements->firstItem() ?? 0, 'to' => $movements->lastItem() ?? 0, 'total' => $movements->total()])); ?>

            </div>

            <?php echo e($movements->links('vendor.pagination.custom')); ?>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\pos-management\resources\views/stock/index.blade.php ENDPATH**/ ?>