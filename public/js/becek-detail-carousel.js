(function () {
    function initializeCarousel(carousel) {
        const slides = Array.from(carousel.querySelectorAll('[data-carousel-slide]'));
        const dots = Array.from(carousel.querySelectorAll('[data-carousel-dot]'));
        const previousButton = carousel.querySelector('[data-carousel-prev]');
        const nextButton = carousel.querySelector('[data-carousel-next]');
        const currentLabel = carousel.querySelector('[data-carousel-current]');

        if (slides.length < 2) return;

        let currentIndex = 0;
        let pointerStartX = null;
        let pointerStartY = null;
        let isPointerDown = false;
        let suppressClickUntil = 0;

        function normalizeIndex(index) {
            return (index + slides.length) % slides.length;
        }

        function showSlide(index) {
            currentIndex = normalizeIndex(index);

            slides.forEach(function (slide, slideIndex) {
                const isActive = slideIndex === currentIndex;
                slide.classList.toggle('active', isActive);
                slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
            });

            dots.forEach(function (dot, dotIndex) {
                const isActive = dotIndex === currentIndex;
                dot.classList.toggle('active', isActive);
                dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            if (currentLabel) currentLabel.textContent = String(currentIndex + 1);
        }

        function showPrevious() {
            showSlide(currentIndex - 1);
        }

        function showNext() {
            showSlide(currentIndex + 1);
        }

        if (previousButton) {
            previousButton.addEventListener('click', function (event) {
                event.stopPropagation();
                showPrevious();
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', function (event) {
                event.stopPropagation();
                showNext();
            });
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function (event) {
                event.stopPropagation();
                showSlide(Number(dot.getAttribute('data-carousel-dot')) || 0);
            });
        });

        carousel.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                showPrevious();
            }

            if (event.key === 'ArrowRight') {
                event.preventDefault();
                showNext();
            }
        });

        carousel.addEventListener('click', function (event) {
            if (Date.now() < suppressClickUntil) {
                event.preventDefault();
                return;
            }

            if (event.target.closest('button, a')) return;

            const bounds = carousel.getBoundingClientRect();
            const clickedOnLeft = event.clientX < bounds.left + (bounds.width / 2);
            clickedOnLeft ? showPrevious() : showNext();
        });

        carousel.addEventListener('pointerdown', function (event) {
            if (event.pointerType === 'mouse' && event.button !== 0) return;
            pointerStartX = event.clientX;
            pointerStartY = event.clientY;
            isPointerDown = true;
            carousel.classList.add('is-dragging');
        });

        carousel.addEventListener('pointerup', function (event) {
            if (!isPointerDown || pointerStartX === null || pointerStartY === null) return;

            const distanceX = event.clientX - pointerStartX;
            const distanceY = event.clientY - pointerStartY;
            const isHorizontalSwipe = Math.abs(distanceX) > 48 && Math.abs(distanceX) > Math.abs(distanceY);

            if (isHorizontalSwipe) {
                suppressClickUntil = Date.now() + 350;
                distanceX > 0 ? showPrevious() : showNext();
            }

            pointerStartX = null;
            pointerStartY = null;
            isPointerDown = false;
            carousel.classList.remove('is-dragging');
        });

        carousel.addEventListener('pointercancel', function () {
            pointerStartX = null;
            pointerStartY = null;
            isPointerDown = false;
            carousel.classList.remove('is-dragging');
        });

        carousel.querySelectorAll('img').forEach(function (image) {
            image.addEventListener('dragstart', function (event) {
                event.preventDefault();
            });
        });

        showSlide(0);
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-detail-carousel]').forEach(initializeCarousel);
    });
})();
