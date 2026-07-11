{{--
    এই ফাইলটি config/ui.php থেকে মান পড়ে CSS Variable আকারে <head>-এ বসিয়ে দেয়।
    এর ফলে config/ui.php-তে একটি মাত্র লাইন পরিবর্তন করলেই পুরো Application-এর
    Color, Spacing, Radius, Shadow, Typography, Sidebar/Header Size ইত্যাদি
    স্বয়ংক্রিয়ভাবে আপডেট হয়ে যায় — কোনো CSS ফাইল স্পর্শ না করেই।

    এই ফাইলটি নিজে থেকে UI-তে কোনো পরিবর্তন আনে না; এটি শুধু single source
    of truth (config/ui.php) কে CSS-এ translate করে।
--}}
<style id="theme-vars">
    :root{

        /* Colors */
        --primary: {{ config('ui.colors.primary') }};
        --primary-dark: {{ config('ui.colors.primary_dark') }};
        --primary-light: {{ config('ui.colors.primary_light') }};
        --secondary: {{ config('ui.colors.secondary') }};

        --success: {{ config('ui.colors.success') }};
        --warning: {{ config('ui.colors.warning') }};
        --warning-text: {{ config('ui.colors.warning_text') }};
        --warning-bg: {{ config('ui.colors.warning_bg') }};
        --danger: {{ config('ui.colors.danger') }};
        --danger-dark: {{ config('ui.colors.danger_dark') }};
        --danger-bg: {{ config('ui.colors.danger_bg') }};
        --info: {{ config('ui.colors.info') }};
        --info-dark: {{ config('ui.colors.info_dark') }};
        --info-bg: {{ config('ui.colors.info_bg') }};
        --info-text: {{ config('ui.colors.info_text') }};

        --white: {{ config('ui.colors.white') }};
        --background: {{ config('ui.colors.background') }};

        --text: {{ config('ui.colors.text') }};
        --text-light: {{ config('ui.colors.text_light') }};
        --text-muted: {{ config('ui.colors.text_muted') }};
        --text-faint: {{ config('ui.colors.text_faint') }};

        --border: {{ config('ui.colors.border') }};
        --border-input: {{ config('ui.colors.border_input') }};

        /* Typography */
        --font-family: {{ config('ui.typography.font_family') }};
        --font-size-base: {{ config('ui.typography.font_size_base') }};
        --font-size-sm: {{ config('ui.typography.font_size_sm') }};
        --font-size-lg: {{ config('ui.typography.font_size_lg') }};
        --line-height-base: {{ config('ui.typography.line_height_base') }};

        /* Layout */
        --sidebar-width: {{ config('ui.layout.sidebar_width') }};
        --sidebar-width-collapsed: {{ config('ui.layout.sidebar_width_collapsed') }};
        --header-height: {{ config('ui.layout.header_height') }};
        --header-height-mobile: {{ config('ui.layout.header_height_mobile') }};
        --content-padding: {{ config('ui.layout.content_padding') }};
        --content-padding-mobile: {{ config('ui.layout.content_padding_mobile') }};
        --container-max-width: {{ config('ui.layout.container_max_width') }};

        /* Shape & elevation */
        --radius: {{ config('ui.shape.radius') }};
        --radius-sm: {{ config('ui.shape.radius_sm') }};
        --radius-lg: {{ config('ui.shape.radius_lg') }};
        --radius-pill: {{ config('ui.shape.radius_pill') }};
        --radius-circle: {{ config('ui.shape.radius_circle') }};
        --shadow: {{ config('ui.shape.shadow') }};
        --shadow-lg: {{ config('ui.shape.shadow_lg') }};

        /* Motion */
        --transition-fast: {{ config('ui.motion.transition_fast') }};
        --transition: {{ config('ui.motion.transition') }};
        --transition-slow: {{ config('ui.motion.transition_slow') }};

        /* Components */
        --control-height: {{ config('ui.components.control_height') }};
        --button-height: {{ config('ui.components.button_height') }};
        --button-padding-x: {{ config('ui.components.button_padding_x') }};
        --button-padding-y: {{ config('ui.components.button_padding_y') }};
        --button-sm-padding: {{ config('ui.components.button_sm_padding') }};
        --button-lg-padding: {{ config('ui.components.button_lg_padding') }};

        /* Spacing */
        --spacing-unit: {{ config('ui.spacing.unit') }};

        /* Z-index */
        --z-sidebar: {{ config('ui.z_index.sidebar') }};
        --z-header: {{ config('ui.z_index.header') }};
        --z-dropdown: {{ config('ui.z_index.dropdown') }};
        --z-modal: {{ config('ui.z_index.modal') }};
        --z-toast: {{ config('ui.z_index.toast') }};
    }
</style>
