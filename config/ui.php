<?php

/**
 * ==========================================================================
 *  CENTRAL THEME CONFIGURATION  —  "Change Once, Apply Everywhere"
 * ==========================================================================
 *
 * এই ফাইলের যেকোনো মান পরিবর্তন করলে সম্পূর্ণ Application-এর UI
 * স্বয়ংক্রিয়ভাবে আপডেট হয়ে যাবে। কোনো Blade view বা CSS ফাইলে
 * গিয়ে আলাদাভাবে কিছু পরিবর্তন করার প্রয়োজন নেই।
 *
 * এই মানগুলো `resources/views/layouts/partials/theme-vars.blade.php`
 * এর মাধ্যমে CSS Custom Properties (`:root { --primary: ... }`) হিসেবে
 * প্রতিটি Page-এর <head>-এ Inject হয় এবং সব CSS ফাইল
 * (buttons.css, table.css, forms.css, sidebar.css ...) সেই ভ্যারিয়েবলগুলো
 * ব্যবহার করে। ফলে একটি মাত্র লাইন পরিবর্তনেই পুরো Application বদলে যায়।
 *
 * উদাহরণ: শুধু 'primary' => '#198754' কে '#0d6efd' করলেই সব বাটন,
 * সাইডবার, লিংক, ব্যাজ, এক্টিভ-স্টেট ইত্যাদির রঙ বদলে যাবে।
 */

return [

    // ---------------------------------------------------------------------
    // Brand / App
    // ---------------------------------------------------------------------
    'app_name' => env('APP_NAME', 'POS Management'),

    // ---------------------------------------------------------------------
    // Colors — শুধুমাত্র এখানে পরিবর্তন করলেই পুরো অ্যাপের রঙ বদলে যাবে
    // ---------------------------------------------------------------------
    'colors' => [
        'primary'       => '#198754',
        'primary_dark'  => '#146c43',
        'primary_light' => '#d1f4df',

        'secondary'     => '#198754', // btn-secondary বর্তমানে primary অনুসরণ করে (outline style)

        'success'       => '#20c997',
        'warning'       => '#ffc107',
        'warning_text'  => '#856404',
        'warning_bg'    => '#fff3cd',
        'danger'        => '#dc3545',
        'danger_dark'   => '#bb2d3b',
        'danger_bg'     => '#fde2e2',
        'info'          => '#0dcaf0',
        'info_dark'     => '#0aa7c6',
        'info_bg'       => '#d9f3ff',
        'info_text'     => '#0d6efd',

        'white'         => '#ffffff',
        'background'    => '#f5f7fa',

        'text'          => '#222222',
        'text_light'    => '#666666',
        'text_muted'    => '#888888',
        'text_faint'    => '#777777',

        'border'        => '#e5e7eb',
        'border_input'  => '#dcdcdc',
    ],

    // ---------------------------------------------------------------------
    // Typography
    // ---------------------------------------------------------------------
    'typography' => [
        'font_family'      => '"Segoe UI", Tahoma, Geneva, Verdana, sans-serif',
        'font_size_base'   => '15px',
        'font_size_sm'     => '13px',
        'font_size_lg'     => '18px',
        'line_height_base' => '1.5',
    ],

    // ---------------------------------------------------------------------
    // Layout dimensions
    // ---------------------------------------------------------------------
    'layout' => [
        'sidebar_width'          => '260px',
        'sidebar_width_collapsed'=> '80px',
        'header_height'          => '70px',
        'header_height_mobile'   => '60px',
        'content_padding'        => '25px',
        'content_padding_mobile' => '15px',
        'container_max_width'    => '1600px',
    ],

    // ---------------------------------------------------------------------
    // Shape, elevation & motion — সব Card/Button/Input/Modal-এর জন্য অভিন্ন
    // ---------------------------------------------------------------------
    'shape' => [
        'radius'        => '10px',
        'radius_sm'     => '8px',
        'radius_lg'     => '12px',
        'radius_pill'   => '50px',
        'radius_circle' => '50%',
        'shadow'        => '0 5px 18px rgba(0,0,0,.08)',
        'shadow_lg'     => '0 10px 30px rgba(0,0,0,.12)',
    ],

    'motion' => [
        'transition_fast' => '.15s ease',
        'transition'      => '.25s ease',
        'transition_slow' => '.35s ease',
    ],

    // ---------------------------------------------------------------------
    // Components — height/padding shared by every Button, Input & Select
    // ---------------------------------------------------------------------
    'components' => [
        'control_height'    => '48px', // input, select, textarea min-height
        'button_height'     => '48px',
        'button_padding_x'  => '22px',
        'button_padding_y'  => '12px',
        'button_sm_padding' => '8px 14px',
        'button_lg_padding' => '15px 30px',
    ],

    // ---------------------------------------------------------------------
    // Spacing scale — ৮px ভিত্তিক Consistent Spacing System
    // ---------------------------------------------------------------------
    'spacing' => [
        'unit' => '8px',
    ],

    // ---------------------------------------------------------------------
    // Z-index scale — সব Overlapping Element (sidebar, header, modal, toast)
    // এই কেন্দ্রীয় স্কেল অনুসরণ করবে যাতে Stacking Conflict না হয়
    // ---------------------------------------------------------------------
    'z_index' => [
        'sidebar' => 999,
        'header'  => 100,
        'dropdown'=> 500,
        'modal'   => 1000,
        'toast'   => 1100,
    ],

    // ---------------------------------------------------------------------
    // Global stylesheet load order.
    //
    // নতুন কোনো CSS ফাইল যোগ করতে হলে শুধু এখানে একটি লাইন যোগ করলেই
    // চলবে — layout.blade.php ফাইলে গিয়ে <link> ট্যাগ যোগ করার
    // প্রয়োজন নেই।
    // ---------------------------------------------------------------------
    'stylesheets' => [
        'css/tokens.css',    // design tokens fallback (rarely touched)
        'css/app.css',       // reset + base html/body
        'css/layout.css',    // app shell / grid / page structure
        'css/header.css',
        'css/sidebar.css',
        'css/buttons.css',
        'css/forms.css',
        'css/table.css',
        'css/components.css',// card, alert, badge, modal, auth
        'css/dashboard.css',
        'css/utilities.css',
        'css/responsive.css',
        'css/print.css',
    ],

    'scripts' => [
        'js/app.js',
        'js/sidebar.js',
    ],

    // Lighter stylesheet set for standalone pages (login, forgot/reset password)
    // that don't need the full app shell (sidebar/header/table/dashboard CSS).
    'auth_stylesheets' => [
        'css/tokens.css',
        'css/app.css',
        'css/forms.css',
        'css/buttons.css',
        'css/components.css',
        'css/utilities.css',
    ],
];
