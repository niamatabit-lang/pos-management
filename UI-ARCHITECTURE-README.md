# UI Architecture Refactor — সারসংক্ষেপ

## এখন থেকে UI পরিবর্তন করবেন যেভাবে

**একটি মাত্র ফাইল** — `config/ui.php` — এখন পুরো Application-এর Look & Feel নিয়ন্ত্রণ করে।

```php
'colors' => [
    'primary' => '#198754',   // ← এই একটি লাইন বদলালেই
    ...                       //   সব বাটন, সাইডবার, লিংক, ব্যাজ,
],                            //   একটিভ-স্টেট সব জায়গায় নতুন রঙ প্রয়োগ হবে
```

কীভাবে কাজ করে: `config/ui.php` → `layouts/partials/theme-vars.blade.php` (প্রতিটি
পেজের `<head>`-এ CSS Variable বসিয়ে দেয়) → সব CSS ফাইল সেই ভ্যারিয়েবল ব্যবহার করে।
কোনো Blade view বা CSS ফাইলে গিয়ে হাতে পরিবর্তন করার দরকার নেই।

Sidebar width, Header height, Button/Input height, Border Radius, Shadow, Font,
Spacing Unit, Transition Speed, Z-index — সবকিছুই একইভাবে `config/ui.php` থেকে
নিয়ন্ত্রিত।

নতুন কোনো global CSS ফাইল যোগ করতে চাইলেও `config/ui.php`-এর `stylesheets` লিস্টে
এক লাইন যোগ করলেই চলবে — `layouts/app.blade.php`-এ গিয়ে `<link>` ট্যাগ বসাতে হবে না।

## নতুন ফোল্ডার স্ট্রাকচার

```
config/ui.php                                 ← Single source of truth
resources/views/layouts/
    app.blade.php                              ← Master layout (logged-in পেজ)
    auth.blade.php                             ← Master layout (লগইন/পাসওয়ার্ড পেজ)
    partials/theme-vars.blade.php              ← config/ui.php → CSS Variables
    header.blade.php / sidebar.blade.php / footer.blade.php
resources/views/components/                    ← Reusable Blade Components
    button.blade.php   card.blade.php   badge.blade.php   alert.blade.php
    page-header.blade.php   input.blade.php   select.blade.php
    table-wrapper.blade.php   nav-item.blade.php
public/css/  (ও resources/css/ — মিরর কপি)
    tokens.css       ← static fallback design tokens
    app.css          ← reset + base
    layout.css       ← app shell/grid/page-header/section-title
    header.css       sidebar.css
    buttons.css      forms.css      table.css
    components.css   ← card/alert/badge/auth shell
    dashboard.css    utilities.css   responsive.css   print.css
    pos.css          ← শুধুমাত্র POS (Sales → New Sale) পেজে লোড হয়
```

## Component ব্যবহারের উদাহরণ

Button ডিজাইন পরিবর্তন করতে হলে শুধু `resources/views/components/button.blade.php`
অথবা `public/css/buttons.css` পরিবর্তন করলেই **প্রতিটি পেজের প্রতিটি বাটন** আপডেট
হয়ে যাবে:

```blade
<x-button variant="primary">Save</x-button>
<x-button variant="danger" size="sm">Delete</x-button>
<x-button tag="a" href="{{ route('products.index') }}" variant="secondary">Back</x-button>
```

একইভাবে `<x-card>`, `<x-badge variant="success">`, `<x-alert variant="warning">`,
`<x-page-header :title="..." :subtitle="...">`, `<x-table-wrapper>` — সব পেজে
পুনরায় ব্যবহৃত হচ্ছে।

## যা পরিবর্তন করা হয়নি (নিশ্চিত করা হয়েছে)

- কোনো Controller, Model, Route, Middleware, Permission, Database পরিবর্তন হয়নি।
- সব `route()` নাম, ফর্মের সব `name="..."` ফিল্ড, সব `__('app....')` অনুবাদ-কী,
  `@csrf`/`@method` ডিরেক্টিভ — হুবহু আগের মতো রাখা হয়েছে (script দিয়ে
  automated ভাবে verify করা হয়েছে)।
- কোনো Feature যোগ/বাদ দেওয়া হয়নি — শুধুমাত্র UI markup ও CSS পুনর্গঠন করা হয়েছে।

## একটি ছোট্ট নোট

এই পরিবেশে PHP/Composer না থাকায় সরাসরি Laravel সার্ভার চালিয়ে টেস্ট করা সম্ভব
হয়নি। তাই নিজের সার্ভারে ডিপ্লয় করার পর একবার প্রতিটি মূল পেজ (Dashboard, POS/Sales,
Products, Finance, Reports, Users, Login) ঘুরে দেখে নেওয়ার পরামর্শ থাকছে।
