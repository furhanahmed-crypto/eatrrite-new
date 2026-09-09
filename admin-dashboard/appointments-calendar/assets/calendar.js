(function () {
    'use strict';

    const calendar = document.querySelector('[data-er-calendar]');
    const drawer = document.querySelector('[data-er-drawer]');
    if (!calendar || !drawer) {
        return;
    }

    const nameEl = drawer.querySelector('[data-er-drawer-name]');
    const serviceEl = drawer.querySelector('[data-er-drawer-service]');
    const dateEl = drawer.querySelector('[data-er-drawer-date]');
    const timeEl = drawer.querySelector('[data-er-drawer-time]');
    const blockEl = drawer.querySelector('[data-er-drawer-block]');
    const phoneEl = drawer.querySelector('[data-er-drawer-phone]');
    const meetEl = drawer.querySelector('[data-er-drawer-meet]');

    function closeDrawer() {
        drawer.hidden = true;
        document.body.style.overflow = '';
    }

    function openDrawer(event) {
        const raw = event.currentTarget.getAttribute('data-er-event');
        if (!raw) {
            return;
        }
        const data = JSON.parse(raw);
        nameEl.textContent = data.name || 'Client';
        serviceEl.textContent = data.service || '—';
        dateEl.textContent = data.display_date || data.date || '—';
        timeEl.textContent = (data.display_time || '') + ' – ' + (data.display_meeting_end || '');
        blockEl.textContent = (data.display_time || '') + ' – ' + (data.display_block_end || '') + ' (includes prep)';
        phoneEl.textContent = data.phone || '—';
        if (data.meet_link) {
            meetEl.innerHTML = '<a href="' + data.meet_link + '" target="_blank" rel="noopener">' + data.meet_link + '</a>';
        } else {
            meetEl.textContent = 'Meet link not stored on this row.';
        }
        drawer.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    calendar.querySelectorAll('[data-er-event]').forEach(function (button) {
        button.addEventListener('click', function (clickEvent) {
            clickEvent.preventDefault();
            clickEvent.stopPropagation();
            openDrawer(clickEvent);
        });
    });

    drawer.querySelectorAll('[data-er-drawer-close]').forEach(function (el) {
        el.addEventListener('click', closeDrawer);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !drawer.hidden) {
            closeDrawer();
        }
    });

    const csrf = calendar.getAttribute('data-csrf') || '';
    const date = calendar.getAttribute('data-date') || '';
    const hideUrl = calendar.getAttribute('data-hide-slot-url') || '';
    let hideErrorTimer = 0;

    function showHideError(message) {
        let banner = document.querySelector('[data-er-hide-error]');
        if (!banner) {
            banner = document.createElement('p');
            banner.className = 'er-cal-hide-error';
            banner.setAttribute('data-er-hide-error', '1');
            document.body.appendChild(banner);
        }
        banner.textContent = message;
        banner.hidden = false;
        window.clearTimeout(hideErrorTimer);
        hideErrorTimer = window.setTimeout(function () {
            banner.hidden = true;
        }, 5000);
    }

    function applyHiddenState(row, hidden) {
        row.classList.toggle('is-open', !hidden);
        row.classList.toggle('is-hidden', hidden);
        const status = row.querySelector('[data-er-slot-status]');
        if (status) {
            status.textContent = hidden ? 'Hidden' : 'Open';
        }
    }

    calendar.querySelectorAll('[data-er-hide-slot]').forEach(function (input) {
        input.addEventListener('change', function () {
            const row = input.closest('.er-cal-slot');
            const time = input.getAttribute('data-time') || '';
            const hidden = input.checked;
            if (!row || !time || !hideUrl) {
                return;
            }

            applyHiddenState(row, hidden);
            input.disabled = true;

            fetch(hideUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrf,
                },
                body: JSON.stringify({
                    date: date,
                    time: time,
                    hidden: hidden,
                }),
            })
                .then(function (response) {
                    return response.text().then(function (text) {
                        var payload = {};
                        try {
                            payload = JSON.parse(text);
                        } catch (parseError) {
                            payload = { error: 'Could not update that slot. Redeploy the Apps Script if this keeps failing.' };
                        }
                        return { ok: response.ok && payload && payload.ok, payload: payload };
                    });
                })
                .then(function (result) {
                    if (!result.ok) {
                        throw new Error((result.payload && result.payload.error) || 'Could not update that slot.');
                    }
                })
                .catch(function (error) {
                    input.checked = !hidden;
                    applyHiddenState(row, !hidden);
                    showHideError(error.message || 'Could not update that slot.');
                })
                .finally(function () {
                    input.disabled = false;
                });
        });
    });
})();
