<?php $__env->startSection('title', __('app.finance_title')); ?>

<?php $__env->startSection('content'); ?>

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">
                <?php echo e(__('app.finance_title')); ?>

            </h1>
            <p class="page-subtitle">
                <?php echo e($shop->name); ?> — <?php echo e(__('app.finance_subtitle')); ?>

            </p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div style="background:#d1f4df;color:#198754;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($isShort): ?>
        <div style="background:#fde2e2;color:#dc3545;padding:16px 20px;border-radius:10px;margin-bottom:20px;font-weight:700;">
            ⚠️ <?php echo e(__('app.short_warning', ['amount' => number_format(abs($difference), 2)])); ?>

        </div>
    <?php elseif($isExtra): ?>
        <div style="background:#d1f4df;color:#198754;padding:16px 20px;border-radius:10px;margin-bottom:20px;font-weight:700;">
            ✅ <?php echo e(__('app.extra_notice', ['amount' => number_format($difference, 2)])); ?>

        </div>
    <?php else: ?>
        <div style="background:#d1f4df;color:#198754;padding:16px 20px;border-radius:10px;margin-bottom:20px;font-weight:700;">
            ✅ <?php echo e(__('app.balance_ok')); ?>

        </div>
    <?php endif; ?>

    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-title"><?php echo e(__('app.opening_cash')); ?></div>
            <div class="kpi-value">৳ <?php echo e(number_format($shop->opening_cash, 2)); ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title"><?php echo e(__('app.current_cash_kpi')); ?></div>
            <div class="kpi-value">৳ <?php echo e(number_format($currentCash, 2)); ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title"><?php echo e(__('app.stock_value_kpi')); ?></div>
            <div class="kpi-value">৳ <?php echo e(number_format($stockValue, 2)); ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title"><?php echo e(__('app.total_receivable')); ?></div>
            <div class="kpi-value">৳ <?php echo e(number_format($totalReceivable, 2)); ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title"><?php echo e(__('app.total_payable')); ?></div>
            <div class="kpi-value">৳ <?php echo e(number_format($totalPayable, 2)); ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title"><?php echo e(__('app.net_position')); ?></div>
            <div class="kpi-value">৳ <?php echo e(number_format($netPosition, 2)); ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title"><?php echo e(__('app.month_sales')); ?></div>
            <div class="kpi-value">৳ <?php echo e(number_format($currentMonthSales, 2)); ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title"><?php echo e(__('app.month_profit')); ?></div>
            <div class="kpi-value">৳ <?php echo e(number_format($currentMonthProfit, 2)); ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title"><?php echo e(__('app.month_expense')); ?></div>
            <div class="kpi-value">৳ <?php echo e(number_format($currentMonthExpense, 2)); ?></div>
        </div>

    </div>

    <p style="color:#888;font-size:13px;margin:15px 0 25px;">
        <?php echo e(__('app.finance_formula_note')); ?>

    </p>

    
    <div class="kpi-grid" style="margin-bottom:10px;">

        <div class="kpi-card">
            <div class="kpi-title"><?php echo e(__('app.total_net_profit')); ?></div>
            <div class="kpi-value">৳ <?php echo e(number_format($totalNetProfit, 2)); ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title"><?php echo e(__('app.total_withdrawn')); ?></div>
            <div class="kpi-value">৳ <?php echo e(number_format($totalWithdrawn, 2)); ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title"><?php echo e(__('app.available_profit')); ?></div>
            <div class="kpi-value" style="color: <?php echo e($availableProfit < 0 ? '#dc3545' : '#198754'); ?>;">
                ৳ <?php echo e(number_format($availableProfit, 2)); ?>

            </div>
        </div>

    </div>

    <p style="color:#888;font-size:13px;margin:0 0 25px;">
        <?php echo e(__('app.withdrawal_note')); ?>

    </p>

    <div class="table-wrapper" style="margin-bottom:25px;">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;"><?php echo e(__('app.withdraw_profit')); ?></h2>
        </div>

        <form method="POST" action="<?php echo e(route('profit-withdrawals.store')); ?>" style="padding:18px;">
            <?php echo csrf_field(); ?>
            <div class="form-row-3">
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
                    <label class="form-label"><?php echo e(__('app.date')); ?></label>
                    <input type="date" name="date" class="form-control" value="<?php echo e(old('date', now()->toDateString())); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('app.note')); ?></label>
                    <input type="text" name="note" class="form-control" placeholder="<?php echo e(__('app.optional')); ?>" value="<?php echo e(old('note')); ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo e(__('app.record_withdrawal')); ?></button>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th><?php echo e(__('app.date')); ?></th>
                    <th class="text-right"><?php echo e(__('app.amount_taka')); ?></th>
                    <th><?php echo e(__('app.taken_by')); ?></th>
                    <th><?php echo e(__('app.note')); ?></th>
                    <th class="text-right"><?php echo e(__('app.action')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $profitWithdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdrawal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e(optional($withdrawal->date)->format('d M Y')); ?></td>
                        <td class="text-right">৳ <?php echo e(number_format($withdrawal->amount, 2)); ?></td>
                        <td><?php echo e($withdrawal->withdrawnBy->name ?? '-'); ?></td>
                        <td><?php echo e($withdrawal->note ?? '-'); ?></td>
                        <td class="text-right">
                            <form method="POST" action="<?php echo e(route('profit-withdrawals.destroy', $withdrawal)); ?>" onsubmit="return confirm('<?php echo e(__('app.confirm_delete_withdrawal')); ?>');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm"><?php echo e(__('app.delete')); ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="table-empty"><?php echo e(__('app.no_withdrawals_yet')); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    

    <div class="table-wrapper" style="margin-bottom:25px;">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;"><?php echo e(__('app.update_cash')); ?></h2>
        </div>

        <form method="POST" action="<?php echo e(route('finance.update-cash')); ?>" style="padding:18px;display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            <?php echo csrf_field(); ?>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label"><?php echo e(__('app.opening_cash')); ?> (৳)</label>
                <input type="number" step="0.01" min="0" name="opening_cash" class="form-control" value="<?php echo e($shop->opening_cash); ?>">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label"><?php echo e(__('app.current_cash_kpi')); ?> (৳)</label>
                <input type="number" step="0.01" min="0" name="current_cash" class="form-control" value="<?php echo e($shop->current_cash); ?>">
            </div>
            <button type="submit" class="btn btn-primary"><?php echo e(__('app.update')); ?></button>
        </form>
    </div>

    <div class="table-wrapper" style="margin-bottom:25px;">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;"><?php echo e(__('app.receivables_list')); ?></h2>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th><?php echo e(__('app.date')); ?></th>
                    <th><?php echo e(__('app.invoice')); ?></th>
                    <th><?php echo e(__('app.customer_name')); ?></th>
                    <th class="text-right"><?php echo e(__('app.total_bill')); ?></th>
                    <th class="text-right"><?php echo e(__('app.paid')); ?></th>
                    <th class="text-right"><?php echo e(__('app.due')); ?></th>
                    <th class="text-right"><?php echo e(__('app.action')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $receivables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($sale->created_at->format('d M Y')); ?></td>
                        <td><?php echo e($sale->invoice_no); ?></td>
                        <td><?php echo e($sale->customer_name ?? '-'); ?></td>
                        <td class="text-right">৳ <?php echo e(number_format($sale->total, 2)); ?></td>
                        <td class="text-right">৳ <?php echo e(number_format($sale->paid_amount, 2)); ?></td>
                        <td class="text-right"><strong>৳ <?php echo e(number_format($sale->due_amount, 2)); ?></strong></td>
                        <td class="text-right">
                            <form method="POST" action="<?php echo e(route('finance.receivables.payment', $sale)); ?>" style="display:inline-flex;gap:4px;">
                                <?php echo csrf_field(); ?>
                                <input type="number" step="0.01" min="0.01" max="<?php echo e($sale->due_amount); ?>" name="payment_amount" class="form-control" style="width:100px;height:34px;padding:0 6px;" placeholder="<?php echo e(__('app.amount')); ?>" required>
                                <button type="submit" class="btn btn-secondary btn-sm"><?php echo e(__('app.collect')); ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="table-empty"><?php echo e(__('app.no_receivables')); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-wrapper" style="margin-bottom:25px;">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;"><?php echo e(__('app.add_new_receivable')); ?></h2>
        </div>
        <p style="color:#888;font-size:13px;padding:0 18px;margin:6px 0 0;">
            <?php echo e(__('app.add_receivable_note')); ?>

        </p>

        <form method="POST" action="<?php echo e(route('receivables.store')); ?>" style="padding:18px;">
            <?php echo csrf_field(); ?>
            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('app.owed_by')); ?> <span class="required">*</span></label>
                    <input type="text" name="party_name" class="form-control" placeholder="<?php echo e(__('app.person_customer_name')); ?>" value="<?php echo e(old('party_name')); ?>">
                    <?php $__errorArgs = ['party_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                    <label class="form-label"><?php echo e(__('app.date')); ?></label>
                    <input type="date" name="date" class="form-control" value="<?php echo e(old('date', now()->toDateString())); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label"><?php echo e(__('app.note')); ?></label>
                <input type="text" name="note" class="form-control" placeholder="<?php echo e(__('app.optional')); ?>" value="<?php echo e(old('note')); ?>">
            </div>
            <button type="submit" class="btn btn-primary"><?php echo e(__('app.add_receivable')); ?></button>
        </form>
    </div>

    <div class="table-wrapper" style="margin-bottom:25px;">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;"><?php echo e(__('app.manual_receivables_list')); ?></h2>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th><?php echo e(__('app.date')); ?></th>
                    <th><?php echo e(__('app.owed_by')); ?></th>
                    <th class="text-right"><?php echo e(__('app.total')); ?></th>
                    <th class="text-right"><?php echo e(__('app.collected')); ?></th>
                    <th class="text-right"><?php echo e(__('app.due')); ?></th>
                    <th><?php echo e(__('app.note')); ?></th>
                    <th class="text-right"><?php echo e(__('app.action')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $manualReceivables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receivable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e(optional($receivable->date)->format('d M Y')); ?></td>
                        <td><?php echo e($receivable->party_name); ?></td>
                        <td class="text-right">৳ <?php echo e(number_format($receivable->amount, 2)); ?></td>
                        <td class="text-right">৳ <?php echo e(number_format($receivable->paid_amount, 2)); ?></td>
                        <td class="text-right">
                            <?php if($receivable->isPaidOff()): ?>
                                <span class="badge badge-success"><?php echo e(__('app.collection_done')); ?></span>
                            <?php else: ?>
                                ৳ <?php echo e(number_format($receivable->dueAmount(), 2)); ?>

                            <?php endif; ?>
                        </td>
                        <td><?php echo e($receivable->note ?? '-'); ?></td>
                        <td class="text-right">
                            <div style="display:flex;gap:6px;justify-content:flex-end;flex-wrap:wrap;">
                                <?php if (! ($receivable->isPaidOff())): ?>
                                    <form method="POST" action="<?php echo e(route('receivables.payment', $receivable)); ?>" style="display:inline-flex;gap:4px;">
                                        <?php echo csrf_field(); ?>
                                        <input type="number" step="0.01" min="0.01" max="<?php echo e($receivable->dueAmount()); ?>" name="payment_amount" class="form-control" style="width:100px;height:34px;padding:0 6px;" placeholder="<?php echo e(__('app.amount')); ?>" required>
                                        <button type="submit" class="btn btn-secondary btn-sm"><?php echo e(__('app.collect')); ?></button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" action="<?php echo e(route('receivables.destroy', $receivable)); ?>" onsubmit="return confirm('<?php echo e(__('app.confirm_delete_receivable')); ?>');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm"><?php echo e(__('app.delete')); ?></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="table-empty"><?php echo e(__('app.no_manual_receivables')); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-wrapper" style="margin-bottom:25px;">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;"><?php echo e(__('app.add_new_payable')); ?></h2>
        </div>

        <form method="POST" action="<?php echo e(route('payables.store')); ?>" style="padding:18px;">
            <?php echo csrf_field(); ?>
            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('app.owed_to')); ?> <span class="required">*</span></label>
                    <input type="text" name="party_name" class="form-control" placeholder="<?php echo e(__('app.supplier_name_eg')); ?>" value="<?php echo e(old('party_name')); ?>">
                    <?php $__errorArgs = ['party_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                    <label class="form-label"><?php echo e(__('app.date')); ?></label>
                    <input type="date" name="date" class="form-control" value="<?php echo e(old('date', now()->toDateString())); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label"><?php echo e(__('app.note')); ?></label>
                <input type="text" name="note" class="form-control" placeholder="<?php echo e(__('app.optional')); ?>" value="<?php echo e(old('note')); ?>">
            </div>
            <button type="submit" class="btn btn-primary"><?php echo e(__('app.add_payable')); ?></button>
        </form>
    </div>

    <div class="table-wrapper">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;"><?php echo e(__('app.payables_list')); ?></h2>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th><?php echo e(__('app.date')); ?></th>
                    <th><?php echo e(__('app.owed_to')); ?></th>
                    <th class="text-right"><?php echo e(__('app.total')); ?></th>
                    <th class="text-right"><?php echo e(__('app.paid')); ?></th>
                    <th class="text-right"><?php echo e(__('app.due')); ?></th>
                    <th><?php echo e(__('app.note')); ?></th>
                    <th class="text-right"><?php echo e(__('app.action')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $payables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e(optional($payable->date)->format('d M Y')); ?></td>
                        <td><?php echo e($payable->party_name); ?></td>
                        <td class="text-right">৳ <?php echo e(number_format($payable->amount, 2)); ?></td>
                        <td class="text-right">৳ <?php echo e(number_format($payable->paid_amount, 2)); ?></td>
                        <td class="text-right">
                            <?php if($payable->isPaidOff()): ?>
                                <span class="badge badge-success"><?php echo e(__('app.paid_off')); ?></span>
                            <?php else: ?>
                                ৳ <?php echo e(number_format($payable->dueAmount(), 2)); ?>

                            <?php endif; ?>
                        </td>
                        <td><?php echo e($payable->note ?? '-'); ?></td>
                        <td class="text-right">
                            <div style="display:flex;gap:6px;justify-content:flex-end;flex-wrap:wrap;">
                                <?php if (! ($payable->isPaidOff())): ?>
                                    <form method="POST" action="<?php echo e(route('payables.payment', $payable)); ?>" style="display:inline-flex;gap:4px;">
                                        <?php echo csrf_field(); ?>
                                        <input type="number" step="0.01" min="0.01" max="<?php echo e($payable->dueAmount()); ?>" name="payment_amount" class="form-control" style="width:100px;height:34px;padding:0 6px;" placeholder="<?php echo e(__('app.amount')); ?>" required>
                                        <button type="submit" class="btn btn-secondary btn-sm"><?php echo e(__('app.payment')); ?></button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" action="<?php echo e(route('payables.destroy', $payable)); ?>" onsubmit="return confirm('<?php echo e(__('app.confirm_delete_payable')); ?>');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm"><?php echo e(__('app.delete')); ?></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="table-empty"><?php echo e(__('app.no_payables')); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\pos-management\resources\views/finance/index.blade.php ENDPATH**/ ?>