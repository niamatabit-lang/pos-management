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

    // মোবাইলে/ছোট স্ক্রিনে সাইডবার collapse/expand করার বাটন
    var menuToggle = document.getElementById('menuToggle');
    var sidebar = document.querySelector('.sidebar');

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
        });
    }

});
