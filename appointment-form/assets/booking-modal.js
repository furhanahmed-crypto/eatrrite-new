(function () {
    'use strict';

    const modal = document.getElementById('er-booking-modal');
    if (!modal) {
        return;
    }

    function openModal(event) {
        if (event) {
            event.preventDefault();
        }
        modal.hidden = false;
        document.body.classList.add('er-booking-open');
    }

    function closeModal() {
        if (!document.getElementById('er-slot-modal')?.hidden) {
            return;
        }
        modal.hidden = true;
        document.body.classList.remove('er-booking-open');
    }

    document.querySelectorAll('[data-er-open-booking]').forEach(function (el) {
        el.addEventListener('click', openModal);
    });
    modal.querySelectorAll('[data-er-close-booking]').forEach(function (el) {
        el.addEventListener('click', closeModal);
    });
    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape' || modal.hidden) {
            return;
        }
        const slots = document.getElementById('er-slot-modal');
        if (slots && !slots.hidden) {
            return;
        }
        closeModal();
    }, true);
})();
