<?php $__env->startSection('title', __('app.nav_expenses')); ?>

<?php $__env->startSection('content'); ?>

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title"><?php echo e(__('app.nav_expenses')); ?></h1>
            <p class="page-subtitle"><?php echo e(__('app.expenses_subtitle')); ?></p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div style="background:#d1f4df;color:#198754;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="kpi-grid" style="grid-template-columns:repeat(2,1fr);margin-bottom:20px;">
        <div class="kpi-card">
            <div class="kpi-title"><?php echo e(__('app.month_total_expense')); ?></div>
            <div class="kpi-value">৳ <?php echo e(number_format($totalThisMonth, 2)); ?></div>
        </div>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <h2 style="font-size:18px;margin-bottom:15px;"><?php echo e(__('app.add_new_expense')); ?></h2>

        <form method="POST" action="<?php echo e(route('expenses.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-row-4">
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('app.expense_title')); ?> <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="<?php echo e(__('app.expense_title_eg')); ?>" value="<?php echo e(old('title')); ?>">
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label"><?php echo e(__('app.category')); ?></label>
                    <select name="category" class="form-select">
                        <option value="">-- <?php echo e(__('app.select')); ?> --</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(old('category') == $key): echo 'selected'; endif; ?>><?php echo e(__('app.expense_cat_' . $key)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label"><?php echo e(__('app.amount_taka')); ?> <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="<?php echo e(old('amount')); ?>">
                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label"><?php echo e(__('app.date')); ?> <span class="required">*</span></label>
                    <input type="date" name="date" class="form-control" value="<?php echo e(old('date', now()->toDateString())); ?>">
                    <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label"><?php echo e(__('app.note')); ?></label>
                <input type="text" name="note" class="form-control" placeholder="<?php echo e(__('app.optional')); ?>" value="<?php echo e(old('note')); ?>">
            </div>

            <button type="submit" class="btn btn-primary"><?php echo e(__('app.add_expense')); ?></button>
        </form>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo e(__('app.date')); ?></th>
                    <th><?php echo e(__('app.expense_title')); ?></th>
                    <th><?php echo e(__('app.category')); ?></th>
                    <th class="text-right"><?php echo e(__('app.amount')); ?></th>
                    <th><?php echo e(__('app.note')); ?></th>
                    <th class="text-right"><?php echo e(__('app.action')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($expense->date->format('d M Y')); ?></td>
                        <td><?php echo e($expense->title); ?></td>
                        <td><?php echo e($expense->category ? __('app.expense_cat_' . $expense->category) : '-'); ?></td>
                        <td class="text-right">৳ <?php echo e(number_format($expense->amount, 2)); ?></td>
                        <td><?php echo e($expense->note ?? '-'); ?></td>
                        <td class="text-right">
                            <form method="POST" action="<?php echo e(route('expenses.destroy', $expense)); ?>" onsubmit="return confirm('<?php echo e(__('app.confirm_delete')); ?>');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm"><?php echo e(__('app.delete')); ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="table-empty"><?php echo e(__('app.no_expenses')); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="table-footer">
            <div>
                <?php echo e(__('app.showing_results', ['from' => $expenses->firstItem() ?? 0, 'to' => $expenses->lastItem() ?? 0, 'total' => $expenses->total()])); ?>

            </div>

            <?php echo e($expenses->links('vendor.pagination.custom')); ?>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\pos-management\resources\views/expenses/index.blade.php ENDPATH**/ ?>