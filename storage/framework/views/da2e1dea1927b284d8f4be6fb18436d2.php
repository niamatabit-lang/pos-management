<header class="header">

    <div class="header-left">

        <button id="menuToggle" class="menu-toggle">

            <svg xmlns="http://www.w3.org/2000/svg"
                 width="22"
                 height="22"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M4 6h16M4 12h16M4 18h16"/>

            </svg>

        </button>

        <div class="header-search">

            <input
                type="text"
                placeholder="<?php echo e(__('app.search_placeholder')); ?>">

        </div>

    </div>

    <div class="header-right">

        <form method="POST" action="<?php echo e(route('locale.switch', app()->getLocale() === 'bn' ? 'en' : 'bn')); ?>" style="display:flex;">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-secondary btn-sm" title="<?php echo e(__('app.switch_language')); ?>">
                <?php if(app()->getLocale() === 'bn'): ?>
                    English
                <?php else: ?>
                    বাংলা
                <?php endif; ?>
            </button>
        </form>

        <form method="POST" action="<?php echo e(route('shops.switch')); ?>" class="shop-switcher" style="display:flex;align-items:center;gap:8px;background:#eafaf1;border:1px solid #b7e4c7;padding:6px 12px;border-radius:8px;">
            <?php echo csrf_field(); ?>
            <span style="font-size:12px;font-weight:700;color:#198754;white-space:nowrap;"><?php echo e(__('app.current_shop')); ?></span>
            <select name="shop_id" onchange="this.form.submit()" class="form-select" style="min-width:160px;">
                <?php $__currentLoopData = $allShops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($shop->id); ?>" <?php if($currentShop->id === $shop->id): echo 'selected'; endif; ?>>
                        🏬 <?php echo e($shop->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>

        <div class="user">

            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(auth()->user()->name)); ?>&background=198754&color=fff"
                 alt="User">

            <div class="user-info">

                <div class="user-name">
                    <?php echo e(auth()->user()->name); ?>

                </div>

                <div class="user-role">
                    <?php
                        $roleLabels = [
                            'super_admin' => __('app.role_super_admin'),
                            'shop_owner' => __('app.role_shop_owner'),
                            'manager' => __('app.role_manager'),
                            'employee' => __('app.role_employee'),
                        ];
                    ?>
                    <?php echo e($roleLabels[auth()->user()->role] ?? auth()->user()->role); ?>

                </div>

            </div>

        </div>

        <a href="<?php echo e(route('password.change')); ?>" class="btn btn-secondary btn-sm"><?php echo e(__('app.change_password')); ?></a>

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-secondary btn-sm"><?php echo e(__('app.logout')); ?></button>
        </form>

    </div>

</header>
<?php /**PATH D:\pos-management\resources\views/layouts/header.blade.php ENDPATH**/ ?>