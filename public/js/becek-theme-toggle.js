(function () {
    const STORAGE_KEY = 'becek-theme';
    const DARK = 'dark';
    const LIGHT = 'light';
    const TRANSITION_CLASS = 'theme-transitioning';
    const TRANSITION_DURATION = 560;

    function normalizeTheme(value) {
        return value === LIGHT ? LIGHT : DARK;
    }

    function readTheme() {
        try {
            return normalizeTheme(localStorage.getItem(STORAGE_KEY));
        } catch (error) {
            return DARK;
        }
    }

    function saveTheme(theme) {
        try {
            localStorage.setItem(STORAGE_KEY, theme);
        } catch (error) {
            // Local storage may be blocked. The visual change still applies for the current page.
        }
    }

    function motionIsReduced() {
        return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }

    function startThemeTransition(nextTheme, sourceElement) {
        if (motionIsReduced() || !document.body) return;

        const root = document.documentElement;
        root.classList.remove(TRANSITION_CLASS);
        // Force reflow so repeated theme changes still animate cleanly.
        void root.offsetWidth;
        root.classList.add(TRANSITION_CLASS);

        const overlay = document.createElement('span');
        overlay.className = 'theme-transition-overlay theme-transition-overlay--' + normalizeTheme(nextTheme);
        overlay.setAttribute('aria-hidden', 'true');

        let x = window.innerWidth - 48;
        let y = 48;

        if (sourceElement && sourceElement.getBoundingClientRect) {
            const rect = sourceElement.getBoundingClientRect();
            x = rect.left + rect.width / 2;
            y = rect.top + rect.height / 2;
        }

        overlay.style.setProperty('--theme-x', x + 'px');
        overlay.style.setProperty('--theme-y', y + 'px');
        document.body.appendChild(overlay);

        window.setTimeout(function () {
            root.classList.remove(TRANSITION_CLASS);
        }, TRANSITION_DURATION);

        window.setTimeout(function () {
            overlay.remove();
        }, TRANSITION_DURATION + 160);
    }

    function applyTheme(theme, shouldSave) {
        const normalized = normalizeTheme(theme);
        document.documentElement.setAttribute('data-theme', normalized);
        if (shouldSave) saveTheme(normalized);
        updateThemeControls(normalized);
    }

    function updateThemeControls(theme) {
        const nextIsLight = theme === DARK;
        const nextLabel = nextIsLight ? 'Tema Terang' : 'Tema Gelap';
        const nextIcon = theme === LIGHT ? '☀' : '🌙';
        const currentLabel = theme === LIGHT ? 'Mode terang aktif' : 'Mode gelap aktif';

        document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
            button.setAttribute('aria-label', 'Ganti ke ' + nextLabel.toLowerCase());
            button.setAttribute('aria-pressed', theme === LIGHT ? 'true' : 'false');
            button.setAttribute('title', currentLabel);

            const icon = button.querySelector('[data-theme-icon]');
            const label = button.querySelector('[data-theme-label]');
            const status = button.querySelector('[data-theme-status]');

            if (icon) icon.textContent = nextIcon;
            if (label) label.textContent = button.classList.contains('theme-switch-row') ? 'Ganti Tema' : nextLabel;
            if (status) status.textContent = currentLabel;
        });
    }

    applyTheme(readTheme(), false);

    document.addEventListener('DOMContentLoaded', function () {
        updateThemeControls(readTheme());

        document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                const current = normalizeTheme(document.documentElement.getAttribute('data-theme'));
                const next = current === LIGHT ? DARK : LIGHT;

                startThemeTransition(next, button);
                applyTheme(next, true);

                window.dispatchEvent(new CustomEvent('becek:theme-change', {
                    detail: { theme: next }
                }));
            });
        });
    });
})();
