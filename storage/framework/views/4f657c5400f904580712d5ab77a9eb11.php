<?php $__env->startSection('title', __('app.dashboard')); ?>

<?php $__env->startSection('content'); ?>

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">
                <?php echo e(__('app.dashboard')); ?>

            </h1>
            <p class="page-subtitle">
                <?php echo e($currentShop->name); ?> —
                <?php if($isToday): ?>
                    <?php echo e(__('app.today_summary')); ?>

                <?php else: ?>
                    <?php echo e($selectedDate->translatedFormat('d F, Y')); ?> <?php echo e(__('app.date_summary')); ?>

                <?php endif; ?>
            </p>
        </div>

        <form method="GET" action="<?php echo e(route('dashboard')); ?>" style="display:flex;align-items:center;gap:8px;">
            <label for="dashboardDate" style="font-size:13px;font-weight:600;color:#555;white-space:nowrap;">
                <?php echo e(__('app.select_date')); ?>

            </label>
            <input
                type="date"
                id="dashboardDate"
                name="date"
                class="form-input"
                value="<?php echo e($selectedDate->format('Y-m-d')); ?>"
                max="<?php echo e(now()->format('Y-m-d')); ?>"
                onchange="this.form.submit()">
            <?php if (! ($isToday)): ?>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-secondary btn-sm"><?php echo e(__('app.back_to_today')); ?></a>
            <?php endif; ?>
        </form>
    </div>

    <?php if($lowStockProducts->isNotEmpty()): ?>
        <div style="background:#fff3cd;color:#856404;padding:14px 18px;border-radius:10px;margin-bottom:20px;">
            ⚠️ <strong><?php echo e(__('app.low_stock_alert', ['count' => $lowStockProducts->count()])); ?></strong>
            <?php echo e($lowStockProducts->pluck('name')->take(6)->implode(', ')); ?>

            <?php if($lowStockProducts->count() > 6): ?>
                <?php echo e(__('app.and_more', ['count' => $lowStockProducts->count() - 6])); ?>

            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-title">
                <?php if($isToday): ?> <?php echo e(__('app.today_sales')); ?> <?php else: ?> <?php echo e(__('app.day_sales')); ?> <?php endif; ?>
            </div>
            <div class="kpi-value">৳ <?php echo e(number_format($daySalesTotal, 2)); ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">
                <?php if($isToday): ?> <?php echo e(__('app.today_profit')); ?> <?php else: ?> <?php echo e(__('app.day_profit')); ?> <?php endif; ?>
            </div>
            <div class="kpi-value">৳ <?php echo e(number_format($dayProfit, 2)); ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">
                <?php if($isToday): ?> <?php echo e(__('app.today_cash')); ?> <?php else: ?> <?php echo e(__('app.day_cash')); ?> <?php endif; ?>
            </div>
            <div class="kpi-value">৳ <?php echo e(number_format($dayCash, 2)); ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">
                <?php if($isToday): ?> <?php echo e(__('app.today_expense')); ?> <?php else: ?> <?php echo e(__('app.day_expense')); ?> <?php endif; ?>
            </div>
            <div class="kpi-value">৳ <?php echo e(number_format($dayExpense, 2)); ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title"><?php echo e(__('app.total_stock_value')); ?></div>
            <div class="kpi-value">৳ <?php echo e(number_format($totalStockValue, 2)); ?></div>
        </div>

    </div>

    <div class="table-wrapper">

        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;"><?php echo e(__('app.whats_in_stock')); ?></h2>
            <?php if(auth()->user()->hasPermission('products')): ?>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-secondary btn-sm"><?php echo e(__('app.view_all_products')); ?></a>
            <?php endif; ?>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th><?php echo e(__('app.product')); ?></th>
                    <th class="text-right"><?php echo e(__('app.in_stock')); ?></th>
                    <th class="text-right"><?php echo e(__('app.sale_price')); ?></th>
                    <th class="text-right"><?php echo e(__('app.stock_value')); ?></th>
                    <th><?php echo e(__('app.status')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $stockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($product->name); ?></td>
                        <td class="text-right"><?php echo e(number_format($product->quantity)); ?> <?php echo e($product->unit); ?></td>
                        <td class="text-right">৳ <?php echo e(number_format($product->sell_price, 2)); ?></td>
                        <td class="text-right">৳ <?php echo e(number_format($product->quantity * $product->buy_price, 2)); ?></td>
                        <td>
                            <?php if($product->quantity == 0): ?>
                                <span class="badge badge-danger"><?php echo e(__('app.out_of_stock')); ?></span>
                            <?php elseif($product->isLowStock()): ?>
                                <span class="badge badge-warning"><?php echo e(__('app.low_stock')); ?></span>
                            <?php else: ?>
                                <span class="badge badge-success"><?php echo e(__('app.ok')); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="table-empty">
                            <?php echo e(__('app.no_products_yet')); ?>

                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\pos-management\resources\views/dashboard/index.blade.php ENDPATH**/ ?>