(function () {
    'use strict';

    var mobile = window.matchMedia('(max-width: 899px)');

    function slideWidth(track) {
        return track.clientWidth || 1;
    }

    function currentIndex(track) {
        return Math.round(track.scrollLeft / slideWidth(track));
    }

    function equalize(track) {
        var cards = Array.prototype.slice.call(track.children);
        cards.forEach(function (card) {
            card.style.minHeight = '';
        });
        var tallest = 0;
        cards.forEach(function (card) {
            tallest = Math.max(tallest, card.offsetHeight);
        });
        cards.forEach(function (card) {
            card.style.minHeight = tallest + 'px';
        });
    }

    function goTo(track, index, smooth) {
        var max = track.children.length - 1;
        var next = Math.max(0, Math.min(max, index));
        track.scrollTo({
            left: next * slideWidth(track),
            behavior: smooth ? 'smooth' : 'auto',
        });
        return next;
    }

    function bindTrack(track) {
        var cards = Array.prototype.slice.call(track.children);
        if (cards.length < 2) {
            return;
        }

        var dots = document.createElement('div');
        dots.className = 'co-swipe__dots';
        cards.forEach(function (_, index) {
            var button = document.createElement('button');
            button.type = 'button';
            button.setAttribute('aria-label', 'Slide ' + (index + 1));
            if (index === 0) {
                button.className = 'is-active';
            }
            button.addEventListener('click', function () {
                goTo(track, index, true);
            });
            dots.appendChild(button);
        });
        track.after(dots);

        function paintDots() {
            var active = currentIndex(track);
            Array.prototype.forEach.call(dots.children, function (dot, index) {
                dot.classList.toggle('is-active', index === active);
            });
        }

        var drag = null;
        var skipClick = false;

        function onPointerDown(event) {
            if (event.pointerType === 'mouse' && event.button !== 0) {
                return;
            }
            drag = {
                id: event.pointerId,
                x: event.clientX,
                start: track.scrollLeft,
                moved: false,
            };
            track.classList.add('is-dragging');
            track.setPointerCapture(event.pointerId);
        }

        function onPointerMove(event) {
            if (!drag || event.pointerId !== drag.id) {
                return;
            }
            var delta = event.clientX - drag.x;
            if (Math.abs(delta) > 6) {
                drag.moved = true;
            }
            track.scrollLeft = drag.start - delta;
        }

        function onPointerUp(event) {
            if (!drag || event.pointerId !== drag.id) {
                return;
            }
            var delta = event.clientX - drag.x;
            var index = currentIndex(track);
            if (Math.abs(delta) > slideWidth(track) * 0.18) {
                index += delta < 0 ? 1 : -1;
            }
            skipClick = drag.moved;
            goTo(track, index, true);
            track.classList.remove('is-dragging');
            drag = null;
        }

        function onClick(event) {
            if (!skipClick) {
                return;
            }
            event.preventDefault();
            event.stopPropagation();
            skipClick = false;
        }

        track.addEventListener('pointerdown', onPointerDown);
        track.addEventListener('pointermove', onPointerMove);
        track.addEventListener('pointerup', onPointerUp);
        track.addEventListener('pointercancel', onPointerUp);
        track.addEventListener('click', onClick, true);
        track.addEventListener('scroll', paintDots, { passive: true });

        track._coSwipe = {
            dots: dots,
            paintDots: paintDots,
            teardown: function () {
                track.removeEventListener('pointerdown', onPointerDown);
                track.removeEventListener('pointermove', onPointerMove);
                track.removeEventListener('pointerup', onPointerUp);
                track.removeEventListener('pointercancel', onPointerUp);
                track.removeEventListener('click', onClick, true);
                track.removeEventListener('scroll', paintDots);
                if (dots.parentNode) {
                    dots.parentNode.removeChild(dots);
                }
                cards.forEach(function (card) {
                    card.style.minHeight = '';
                });
                track.scrollLeft = 0;
                track.classList.remove('is-on', 'is-dragging');
                delete track._coSwipe;
            },
            equalize: function () {
                equalize(track);
            },
        };

        track.classList.add('is-on');
        equalize(track);
        goTo(track, 0, false);
        paintDots();
    }

    function sync() {
        document.querySelectorAll('[data-co-swipe]').forEach(function (track) {
            var live = Boolean(track._coSwipe);
            if (mobile.matches && !live) {
                bindTrack(track);
            } else if (!mobile.matches && live) {
                track._coSwipe.teardown();
            } else if (live) {
                track._coSwipe.equalize();
                goTo(track, currentIndex(track), false);
                track._coSwipe.paintDots();
            }
        });
    }

    sync();
    mobile.addEventListener('change', sync);
    window.addEventListener('resize', sync);
})();
