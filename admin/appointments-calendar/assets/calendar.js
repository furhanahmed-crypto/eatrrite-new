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
    const phoneEl = drawer.querySelector('[data-er-drawer-phone]');
    const emailEl = drawer.querySelector('[data-er-drawer-email]');
    const meetEl = drawer.querySelector('[data-er-drawer-meet]');
    const answersEl = drawer.querySelector('[data-er-drawer-answers]');

    function formatDate(iso) {
        const match = /^(\d{4})-(\d{2})-(\d{2})$/.exec(iso || '');
        if (!match) {
            return iso || '—';
        }
        const date = new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3]));
        return date.toLocaleDateString('en-GB', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    }

    function answerNode(text) {
        const parts = String(text).split(/,\s*/).filter(Boolean);
        const asChips = parts.length > 1 && parts.every(function (part) { return part.length <= 56; });
        if (!asChips) {
            const paragraph = document.createElement('p');
            paragraph.className = 'er-cal-qa__a';
            paragraph.textContent = text;
            return paragraph;
        }
        const list = document.createElement('ul');
        list.className = 'er-cal-qa__chips';
        parts.forEach(function (part) {
            const item = document.createElement('li');
            item.textContent = part;
            list.appendChild(item);
        });
        return list;
    }

    function closeDrawer() {
        drawer.hidden = true;
        document.body.style.overflow = '';
    }

    function openDrawer(data) {
        nameEl.textContent = data.name || 'Client';
        serviceEl.textContent = data.service || 'Consultation';
        dateEl.textContent = formatDate(data.date);
        timeEl.textContent = data.display_meeting || data.display_time || data.time || '—';
        phoneEl.textContent = data.phone || '—';
        if (emailEl) {
            emailEl.textContent = data.email || '—';
        }
        if (answersEl) {
            answersEl.replaceChildren();
            const heading = document.createElement('h3');
            heading.textContent = 'Questionnaire';
            answersEl.appendChild(heading);
            const answers = Array.isArray(data.questionnaire) ? data.questionnaire : [];
            if (!answers.length) {
                const empty = document.createElement('p');
                empty.className = 'er-cal-qa__empty';
                empty.textContent = 'Not submitted yet.';
                answersEl.appendChild(empty);
            } else {
                answers.forEach(function (item) {
                    const card = document.createElement('article');
                    card.className = 'er-cal-qa';
                    const label = document.createElement('p');
                    label.className = 'er-cal-qa__q';
                    label.textContent = item.label || '';
                    card.appendChild(label);
                    card.appendChild(answerNode(item.answer || '—'));
                    answersEl.appendChild(card);
                });
            }
        }
        if (data.meet_link) {
            const link = document.createElement('a');
            link.className = 'er-cal-meet';
            link.href = data.meet_link;
            link.target = '_blank';
            link.rel = 'noopener';
            link.textContent = 'Open Google Meet';
            meetEl.replaceChildren(link);
        } else {
            meetEl.textContent = 'Meet link not stored yet.';
        }
        drawer.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    calendar.querySelectorAll('[data-er-event]').forEach(function (button) {
        button.addEventListener('click', function () {
            openDrawer(JSON.parse(button.getAttribute('data-er-event') || '{}'));
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

    calendar.querySelectorAll('[data-er-hide-slot]').forEach(function (button) {
        button.addEventListener('click', function () {
            const time = button.getAttribute('data-time') || '';
            const hidden = button.getAttribute('data-hidden') === '1';
            if (!time) {
                return;
            }
            const ask = window.erConfirm || function () { return Promise.resolve(window.confirm('Continue?')); };
            ask({
                title: hidden ? 'Hide this slot?' : 'Show this slot?',
                description: 'This updates whether customers can book this time.',
                confirmLabel: hidden ? 'Hide slot' : 'Show slot',
            }).then(function (ok) {
            if (!ok) {
                return;
            }
            button.disabled = true;
            fetch(hideUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf },
                body: JSON.stringify({ date: date, time: time, hidden: hidden }),
            })
                .then(function (response) { return response.json(); })
                .then(function (payload) {
                    if (!payload.ok) {
                        throw new Error(payload.error || 'Could not update that slot.');
                    }
                    window.location.reload();
                })
                .catch(function (error) {
                    window.alert(error.message || 'Could not update that slot.');
                    button.disabled = false;
                });
            });
        });
    });
})();
