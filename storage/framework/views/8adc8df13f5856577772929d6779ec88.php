<?php $__env->startSection('title', __('app.nav_stock_ledger')); ?>

<?php $__env->startSection('content'); ?>

<div class="page">

    <div class="page-header">

        <div>
            <h1 class="page-title">
                <?php echo e(__('app.nav_stock_ledger')); ?>

            </h1>

            <p class="page-subtitle">
                <?php echo e(__('app.stock_ledger_subtitle')); ?>

            </p>
        </div>

        <a href="<?php echo e(route('stock.index')); ?>" class="btn btn-secondary">
            &larr; <?php echo e(__('app.back_to_stock_list')); ?>

        </a>

    </div>

    <div class="card" style="margin-bottom:20px;">
        <form method="GET" action="<?php echo e(route('stock.ledger')); ?>" class="form-row-3">

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label"><?php echo e(__('app.date')); ?></label>
                <input type="date" name="date" class="form-control" value="<?php echo e($date->format('Y-m-d')); ?>">
            </div>

            <div class="form-group" style="margin-bottom:0;display:flex;align-items:flex-end;gap:10px;">
                <button type="submit" class="btn btn-primary"><?php echo e(__('app.search')); ?></button>
                <a href="<?php echo e(route('stock.ledger')); ?>" class="btn btn-secondary"><?php echo e(__('app.today')); ?></a>
            </div>

        </form>
    </div>

    <div class="table-wrapper">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;">
                <?php echo e(__('app.stock_account_for_date', ['date' => $date->format('d M Y')])); ?>

            </h2>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th><?php echo e(__('app.product')); ?></th>
                    <th class="text-right"><?php echo e(__('app.opening_stock')); ?></th>
                    <th class="text-right"><?php echo e(__('app.sold_decreased_today')); ?></th>
                    <th class="text-right"><?php echo e(__('app.closing_stock')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($row['product']->name); ?> <small style="color:#999;">(<?php echo e($row['product']->sku); ?>)</small></td>
                        <td class="text-right"><?php echo e(number_format($row['opening'])); ?> <?php echo e($row['product']->unit); ?></td>
                        <td class="text-right"><?php echo e(number_format($row['out_during'])); ?> <?php echo e($row['product']->unit); ?></td>
                        <td class="text-right"><strong><?php echo e(number_format($row['closing'])); ?> <?php echo e($row['product']->unit); ?></strong></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="table-empty">
                            <?php echo e(__('app.no_products_for_date')); ?>

                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\pos-management\resources\views/stock/ledger.blade.php ENDPATH**/ ?>