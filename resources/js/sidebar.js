document.addEventListener('DOMContentLoaded', function () {

    // ক্লিক করলে সাব-মেনু (Products, Categories ইত্যাদি) খোলা/বন্ধ হবে
    document.querySelectorAll('.sidebar-nav > ul > li.has-sub > a').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            var parentItem = link.closest('.has-sub');
            var isOpen = parentItem.classList.contains('active');

            // অন্য সব সাব-মেনু বন্ধ করে শুধু এইটা টগল করা হচ্ছে
            document.querySelectorAll('.sidebar-nav > ul > li.has-sub').forEach(function (item) {
                item.classList.remove('active');
            });

            if (!isOpen) {
                parentItem.classList.add('active');
            }
        });
    });

    // মোবাইলে/ছোট স্ক্রিনে সাইডবার খোলা/বন্ধ করার বাটন
    //
    // ডেস্কটপে (>992px) বাটনটি সাইডবারকে icon-only "collapsed" মোডে নেয়।
    // ট্যাবলেট/মোবাইলে (<=992px, responsive.css এর ব্রেকপয়েন্টের সাথে মিল রেখে)
    // সাইডবারটি স্ক্রিনের বাইরে লুকানো থাকে, বাটনে ক্লিক করলে তা স্লাইড করে
    // ভেতরে আসে এবং একটি ডার্ক ওভারলে দেখা যায়।
    var menuToggle = document.getElementById('menuToggle');
    var sidebar = document.querySelector('.sidebar');
    var overlay = document.getElementById('sidebarOverlay');
    var mobileQuery = window.matchMedia('(max-width: 992px)');

    function openMobileSidebar() {
        sidebar.classList.add('active');
        if (overlay) overlay.classList.add('active');
        if (menuToggle) menuToggle.classList.add('active');
        document.body.classList.add('no-scroll');
    }

    function closeMobileSidebar() {
        sidebar.classList.remove('active');
        if (overlay) overlay.classList.remove('active');
        if (menuToggle) menuToggle.classList.remove('active');
        document.body.classList.remove('no-scroll');
    }

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function () {
            if (mobileQuery.matches) {
                if (sidebar.classList.contains('active')) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }
            } else {
                sidebar.classList.toggle('collapsed');
            }
        });
    }

    // ওভারলেতে ক্লিক করলে সাইডবার বন্ধ হয়ে যাবে
    if (overlay) {
        overlay.addEventListener('click', closeMobileSidebar);
    }

    // Escape চাপলেও সাইডবার বন্ধ হবে
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMobileSidebar();
    });

    // মোবাইল থেকে বড় স্ক্রিনে রিসাইজ করলে খোলা সাইডবার/ওভারলে/স্ক্রল-লক ঠিক করে দেওয়া হয়
    mobileQuery.addEventListener('change', function (e) {
        if (!e.matches) closeMobileSidebar();
    });

});
