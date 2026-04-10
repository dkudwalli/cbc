(function () {
    var body = document.body;
    var header = document.querySelector('.cbc-site-header');
    var toggle = document.querySelector('[data-cbc-mobile-toggle]');
    var panel = document.querySelector('[data-cbc-mobile-panel]');
    var closeControls = document.querySelectorAll('[data-cbc-mobile-close]');

    if (!header) {
        return;
    }

    var syncHeaderState = function () {
        if (window.scrollY > 24) {
            header.classList.add('is-scrolled');
        } else {
            header.classList.remove('is-scrolled');
        }
    };

    syncHeaderState();
    window.addEventListener('scroll', syncHeaderState, { passive: true });

    if (!toggle || !panel) {
        return;
    }

    var closePanel = function () {
        panel.hidden = true;
        body.classList.remove('cbc-mobile-open');
        toggle.setAttribute('aria-expanded', 'false');
    };

    var openPanel = function () {
        panel.hidden = false;
        body.classList.add('cbc-mobile-open');
        toggle.setAttribute('aria-expanded', 'true');
    };

    toggle.addEventListener('click', function () {
        if (panel.hidden) {
            openPanel();
        } else {
            closePanel();
        }
    });

    closeControls.forEach(function (control) {
        control.addEventListener('click', closePanel);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !panel.hidden) {
            closePanel();
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 991 && !panel.hidden) {
            closePanel();
        }
    });

    panel.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closePanel);
    });

    panel.querySelectorAll('.menu-item-has-children').forEach(function (item, index) {
        var submenu = item.querySelector(':scope > ul');
        var link = item.querySelector(':scope > a');

        if (!submenu || !link) {
            return;
        }

        var button = document.createElement('button');
        var buttonId = 'cbc-submenu-' + index;
        button.type = 'button';
        button.className = 'cbc-submenu-toggle';
        button.setAttribute('aria-expanded', 'false');
        button.setAttribute('aria-controls', buttonId);
        button.innerHTML = '<span aria-hidden="true">+</span><span class="screen-reader-text">Toggle submenu</span>';

        submenu.id = buttonId;
        link.insertAdjacentElement('afterend', button);

        button.addEventListener('click', function () {
            var isOpen = item.classList.toggle('is-open');
            button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    });
})();
