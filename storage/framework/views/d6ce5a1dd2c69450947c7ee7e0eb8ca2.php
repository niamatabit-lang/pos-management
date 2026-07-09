<aside class="sidebar">

    <div class="logo">
        <span>POS</span> Management
    </div>

    <nav class="sidebar-nav">

        <ul>

            <li>
                <a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                    <i>🏠</i>
                    <span><?php echo e(__('app.nav_dashboard')); ?></span>
                </a>
            </li>

            <?php if(auth()->user()->hasPermission('sales')): ?>
                <li>
                    <a href="<?php echo e(route('sales.index')); ?>" class="<?php echo e(request()->routeIs('sales.*') ? 'active' : ''); ?>">
                        <i>💰</i>
                        <span><?php echo e(__('app.nav_sales')); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('returns.index')); ?>" class="<?php echo e(request()->routeIs('returns.*') ? 'active' : ''); ?>">
                        <i>↩️</i>
                        <span><?php echo e(__('app.nav_returns')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(auth()->user()->hasPermission('stock')): ?>
                <li>
                    <a href="<?php echo e(route('stock.index')); ?>" class="<?php echo e(request()->routeIs('stock.*') && !request()->routeIs('stock.ledger') ? 'active' : ''); ?>">
                        <i>📈</i>
                        <span><?php echo e(__('app.nav_stock')); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('stock.ledger')); ?>" class="<?php echo e(request()->routeIs('stock.ledger') ? 'active' : ''); ?>">
                        <i>📅</i>
                        <span><?php echo e(__('app.nav_stock_ledger')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(auth()->user()->hasPermission('expenses')): ?>
                <li>
                    <a href="<?php echo e(route('expenses.index')); ?>" class="<?php echo e(request()->routeIs('expenses.*') ? 'active' : ''); ?>">
                        <i>🧾</i>
                        <span><?php echo e(__('app.nav_expenses')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(auth()->user()->isSuperAdmin() || auth()->user()->isShopOwner()): ?>
                <li>
                    <a href="<?php echo e(route('finance.index')); ?>" class="<?php echo e(request()->routeIs('finance.*') ? 'active' : ''); ?>">
                        <i>💵</i>
                        <span><?php echo e(__('app.nav_finance')); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('activity-logs.index')); ?>" class="<?php echo e(request()->routeIs('activity-logs.*') ? 'active' : ''); ?>">
                        <i>📜</i>
                        <span><?php echo e(__('app.nav_activity_logs')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(auth()->user()->hasPermission('reports')): ?>
                <li>
                    <a href="<?php echo e(route('reports.index')); ?>" class="<?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
                        <i>📊</i>
                        <span><?php echo e(__('app.nav_reports')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(auth()->user()->hasPermission('service_fee')): ?>
                <li>
                    <a href="<?php echo e(route('service-fees.index')); ?>" class="<?php echo e(request()->routeIs('service-fees.*') ? 'active' : ''); ?>">
                        <i>📱</i>
                        <span><?php echo e(__('app.nav_service_fees')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(auth()->user()->hasPermission('products')): ?>
                <li>
                    <a href="<?php echo e(route('products.index')); ?>" class="<?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>">
                        <i>📦</i>
                        <span><?php echo e(__('app.nav_products')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(auth()->user()->hasPermission('categories')): ?>
                <li>
                    <a href="<?php echo e(route('categories.index')); ?>" class="<?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>">
                        <i>🏷️</i>
                        <span><?php echo e(__('app.nav_categories')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(auth()->user()->isSuperAdmin() || auth()->user()->isShopOwner()): ?>
                <li>
                    <a href="<?php echo e(route('shops.index')); ?>" class="<?php echo e(request()->routeIs('shops.*') ? 'active' : ''); ?>">
                        <i>🏬</i>
                        <span><?php echo e(__('app.nav_shops')); ?></span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo e(route('users.index')); ?>" class="<?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
                        <i>👤</i>
                        <span>
                            <?php if(auth()->user()->isSuperAdmin()): ?>
                                <?php echo e(__('app.nav_users_owner')); ?>

                            <?php else: ?>
                                <?php echo e(__('app.nav_users_staff')); ?>

                            <?php endif; ?>
                        </span>
                    </a>
                </li>
            <?php endif; ?>

        </ul>

    </nav>

</aside>
<?php /**PATH D:\pos-management\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>