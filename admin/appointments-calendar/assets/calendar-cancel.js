(function () {
    'use strict';

    const calendar = document.querySelector('[data-er-calendar]');
    const drawer = document.querySelector('[data-er-drawer]');
    const cancelBtn = document.querySelector('[data-er-cancel-booking]');
    if (!calendar || !drawer || !cancelBtn) {
        return;
    }

    let current = null;
    const errorEl = drawer.querySelector('[data-er-drawer-error]');

    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-er-event]');
        if (!button) {
            return;
        }
        try {
            current = JSON.parse(button.getAttribute('data-er-event') || '{}');
        } catch (parseError) {
            current = null;
        }
        if (errorEl) {
            errorEl.hidden = true;
            errorEl.textContent = '';
        }
    });

    cancelBtn.addEventListener('click', function () {
        if (!current || !current.date || !current.time) {
            return;
        }
        const ask = window.erConfirm || function () {
            return Promise.resolve(window.confirm('Cancel this booking?'));
        };
        ask({
            title: 'Cancel this booking?',
            description: 'This removes the appointment and frees the slot. The Meet event is cleared when possible.',
            confirmLabel: 'Cancel booking',
            cancelLabel: 'Keep booking',
            danger: true,
        }).then(function (ok) {
        if (!ok) {
            return;
        }

        cancelBtn.disabled = true;
        fetch(calendar.getAttribute('data-cancel-url') || '', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': calendar.getAttribute('data-csrf') || '',
            },
            body: JSON.stringify({
                date: current.date,
                time: current.time,
                phone: current.phone || '',
                name: current.name || '',
            }),
        })
            .then(function (response) {
                return response.json().then(function (payload) {
                    if (!response.ok || !payload.ok) {
                        throw new Error((payload && payload.error) || 'Could not cancel this booking.');
                    }
                });
            })
            .then(function () {
                window.location.reload();
            })
            .catch(function (error) {
                if (errorEl) {
                    errorEl.hidden = false;
                    errorEl.textContent = error.message || 'Could not cancel this booking.';
                }
            })
            .finally(function () {
                cancelBtn.disabled = false;
            });
        });
    });
})();
