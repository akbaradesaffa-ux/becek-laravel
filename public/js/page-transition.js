(function () {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        document.documentElement.classList.remove('page-transition-active');
        return;
    }

    function markReady() {
        document.body.classList.remove('page-leaving');
        document.body.classList.add('page-ready');
    }

    function animateTo(url) {
        if (!url) return;
        document.body.classList.remove('page-ready');
        document.body.classList.add('page-leaving');
        window.setTimeout(function () {
            window.location.href = url;
        }, 180);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', markReady);
    } else {
        markReady();
    }

    window.addEventListener('pageshow', markReady);

    document.addEventListener('click', function (event) {
        const link = event.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href');
        const isModifiedClick = event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0;
        const skipTransition = link.dataset.noTransition === 'true' || link.hasAttribute('download') || link.target === '_blank';

        if (
            event.defaultPrevented ||
            isModifiedClick ||
            skipTransition ||
            !href ||
            href.startsWith('#') ||
            href.startsWith('javascript:') ||
            href.startsWith('mailto:') ||
            href.startsWith('tel:')
        ) {
            return;
        }

        const targetUrl = new URL(link.href, window.location.href);
        if (targetUrl.origin !== window.location.origin) return;

        const currentUrl = new URL(window.location.href);
        if (targetUrl.pathname === currentUrl.pathname && targetUrl.search === currentUrl.search && targetUrl.hash) return;

        event.preventDefault();
        animateTo(targetUrl.href);
    });

    window.becekNavigate = animateTo;
})();
