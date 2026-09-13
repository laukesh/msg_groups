document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Smooth Scroll
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('a[href^="#"]').forEach(function (link) {

        link.addEventListener('click', function (e) {

            const targetId = this.getAttribute('href');

            if (!targetId || targetId === '#') {
                return;
            }

            const target = document.querySelector(targetId);

            if (target) {

                e.preventDefault();

                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Pricing Toggle
    |--------------------------------------------------------------------------
    */

    const pricingButtons =
        document.querySelectorAll('.pill-btn');

    pricingButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            pricingButtons.forEach(function (btn) {
                btn.classList.remove('active');
            });

            this.classList.add('active');

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Header Scroll
    |--------------------------------------------------------------------------
    */

    const header = document.querySelector('header');

    if (header) {

        window.addEventListener('scroll', function () {

            if (window.scrollY > 30) {

                header.classList.add('scrolled');

            } else {

                header.classList.remove('scrolled');

            }

        });

    }

});