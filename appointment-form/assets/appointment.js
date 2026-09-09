(function () {
    'use strict';

    const form = document.getElementById('er-appointment-form');
    const modal = document.getElementById('er-slot-modal');
    if (!form || !modal) {
        return;
    }

    document.body.appendChild(modal);

    const apiBase = form.dataset.api;
    const csrf = form.dataset.csrf;
    const amountRupees = form.dataset.amountRupees || '800';
    const payLabel = 'Pay ₹' + amountRupees + ' and book';
    const alertBox = form.querySelector('[data-er-alert]');
    const submitBtn = form.querySelector('[data-er-submit]');
    const slotLabel = form.querySelector('[data-er-slot-label]');
    const dateInput = form.querySelector('[data-er-date]');
    const timeInput = form.querySelector('[data-er-time]');
    const trigger = form.querySelector('[data-er-open-slots]');
    const daysEl = modal.querySelector('[data-er-days]');
    const timesEl = modal.querySelector('[data-er-times]');
    const monthLabel = modal.querySelector('[data-er-month-label]');
    const selectedDayLabel = modal.querySelector('[data-er-selected-day]');
    const timesMeta = modal.querySelector('[data-er-times-meta]');
    const confirmBtn = modal.querySelector('[data-er-confirm-slot]');
    const prevBtn = modal.querySelector('[data-er-prev-month]');
    const nextBtn = modal.querySelector('[data-er-next-month]');

    const state = {
        availability: null,
        viewMonth: startOfMonth(new Date()),
        selectedDate: dateInput.value || '',
        selectedTime: timeInput.value || '',
        loading: false,
    };

    function pad(value) {
        return String(value).padStart(2, '0');
    }

    function toIsoDate(date) {
        return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());
    }

    function startOfMonth(date) {
        return new Date(date.getFullYear(), date.getMonth(), 1);
    }

    function parseIso(value) {
        const parts = value.split('-').map(Number);
        return new Date(parts[0], parts[1] - 1, parts[2]);
    }

    function formatDayLabel(iso) {
        return parseIso(iso).toLocaleDateString('en-IN', {
            weekday: 'short',
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        });
    }

    function formatTime(hhmm) {
        const [hours, minutes] = hhmm.split(':').map(Number);
        const suffix = hours >= 12 ? 'PM' : 'AM';
        const hour12 = ((hours + 11) % 12) + 1;
        return hour12 + ':' + pad(minutes) + ' ' + suffix;
    }

    function todayIso() {
        return state.availability ? state.availability.from : toIsoDate(new Date());
    }

    function lastIso() {
        return state.availability ? state.availability.to : toIsoDate(new Date());
    }

    function isPastTime(iso, time) {
        const now = new Date();
        const [hours, minutes] = time.split(':').map(Number);
        const slot = parseIso(iso);
        slot.setHours(hours, minutes, 0, 0);
        return slot.getTime() <= now.getTime();
    }

    function availableTimes(iso) {
        if (!state.availability || !state.availability.days) {
            return [];
        }
        return (state.availability.days[iso] || []).filter(function (time) {
            return !isPastTime(iso, time);
        });
    }

    function showAlert(message) {
        alertBox.hidden = !message;
        alertBox.textContent = message || '';
    }

    function setLoading(isLoading) {
        state.loading = isLoading;
        submitBtn.disabled = isLoading;
        submitBtn.textContent = isLoading ? 'Processing…' : payLabel;
    }

    async function request(path, options) {
        const response = await fetch(apiBase + '/' + path, Object.assign({
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrf,
            },
        }, options));
        const payload = await response.json().catch(function () {
            return { ok: false, error: 'Unexpected server response.' };
        });
        if (!response.ok || !payload.ok) {
            throw new Error(payload.error || 'Something went wrong.');
        }
        return payload;
    }

    async function loadAvailability() {
        state.availability = await request('slots.php');
        if (!state.selectedDate) {
            const firstOpen = findFirstOpenDate();
            if (firstOpen) {
                state.selectedDate = firstOpen;
                state.viewMonth = startOfMonth(parseIso(firstOpen));
            }
        }
        renderCalendar();
        renderTimes();
    }

    function findFirstOpenDate() {
        if (!state.availability) {
            return '';
        }
        const cursor = parseIso(state.availability.from);
        const last = parseIso(state.availability.to);
        while (cursor <= last) {
            const iso = toIsoDate(cursor);
            if (availableTimes(iso).length) {
                return iso;
            }
            cursor.setDate(cursor.getDate() + 1);
        }
        return '';
    }

    function renderCalendar() {
        const year = state.viewMonth.getFullYear();
        const month = state.viewMonth.getMonth();
        monthLabel.textContent = state.viewMonth.toLocaleDateString('en-IN', {
            month: 'long',
            year: 'numeric',
        });

        const firstIso = todayIso();
        const finalIso = lastIso();
        prevBtn.disabled = year < parseIso(firstIso).getFullYear() ||
            (year === parseIso(firstIso).getFullYear() && month <= parseIso(firstIso).getMonth());
        nextBtn.disabled = year > parseIso(finalIso).getFullYear() ||
            (year === parseIso(finalIso).getFullYear() && month >= parseIso(finalIso).getMonth());

        const start = new Date(year, month, 1);
        const padCount = start.getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = toIsoDate(new Date());
        daysEl.innerHTML = '';

        for (let i = 0; i < padCount; i += 1) {
            const spacer = document.createElement('span');
            daysEl.appendChild(spacer);
        }

        for (let day = 1; day <= daysInMonth; day += 1) {
            const iso = year + '-' + pad(month + 1) + '-' + pad(day);
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'er-day';
            button.textContent = String(day);
            const open = availableTimes(iso).length > 0;
            const inRange = iso >= firstIso && iso <= finalIso;

            if (!inRange || !open) {
                button.classList.add('is-muted');
                button.disabled = true;
            } else {
                button.classList.add('has-slots');
            }
            if (iso === today) {
                button.classList.add('is-today');
            }
            if (iso === state.selectedDate) {
                button.classList.add('is-selected');
            }
            button.addEventListener('click', function () {
                state.selectedDate = iso;
                state.selectedTime = '';
                renderCalendar();
                renderTimes();
            });
            daysEl.appendChild(button);
        }
    }

    function renderTimes() {
        timesEl.innerHTML = '';

        if (!state.selectedDate) {
            selectedDayLabel.textContent = 'Select a date';
            timesMeta.textContent = '';
            confirmBtn.disabled = true;
            return;
        }

        selectedDayLabel.textContent = formatDayLabel(state.selectedDate);
        const open = availableTimes(state.selectedDate);
        if (state.selectedTime && open.indexOf(state.selectedTime) === -1) {
            state.selectedTime = '';
        }
        timesMeta.textContent = open.length ? open.length + ' slots open' : 'No slots left';
        confirmBtn.disabled = !state.selectedTime;

        if (!state.availability || !state.availability.days) {
            timesEl.innerHTML = '<p class="er-times__empty">Unable to load available slots.</p>';
            return;
        }

        if (!open.length) {
            timesEl.innerHTML = '<p class="er-times__empty">No consultation slots on this date.</p>';
            return;
        }

        open.forEach(function (time) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'er-time';
            button.textContent = formatTime(time);
            if (time === state.selectedTime) {
                button.classList.add('is-selected');
            }
            button.addEventListener('click', function () {
                state.selectedTime = time;
                renderTimes();
            });
            timesEl.appendChild(button);
        });
    }

    function openModal() {
        modal.hidden = false;
        document.body.classList.add('er-modal-open');
        if (!state.availability) {
            timesEl.innerHTML = '<p class="er-times__empty">Loading available slots…</p>';
            loadAvailability().catch(function (error) {
                timesEl.innerHTML = '<p class="er-times__empty">' + error.message + '</p>';
            });
        } else {
            renderCalendar();
            renderTimes();
        }
    }

    function closeModal() {
        modal.hidden = true;
        document.body.classList.remove('er-modal-open');
    }

    function applySlot() {
        if (!state.selectedDate || !state.selectedTime) {
            return;
        }
        dateInput.value = state.selectedDate;
        timeInput.value = state.selectedTime;
        slotLabel.textContent = formatDayLabel(state.selectedDate) + ' · ' + formatTime(state.selectedTime);
        trigger.classList.add('is-filled');
        closeModal();
        showAlert('');
    }

    trigger.addEventListener('click', openModal);
    modal.querySelectorAll('[data-er-close-slots]').forEach(function (el) {
        el.addEventListener('click', closeModal);
    });
    confirmBtn.addEventListener('click', applySlot);
    prevBtn.addEventListener('click', function () {
        state.viewMonth = new Date(state.viewMonth.getFullYear(), state.viewMonth.getMonth() - 1, 1);
        renderCalendar();
    });
    nextBtn.addEventListener('click', function () {
        state.viewMonth = new Date(state.viewMonth.getFullYear(), state.viewMonth.getMonth() + 1, 1);
        renderCalendar();
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.hidden) {
            closeModal();
        }
    });

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        showAlert('');

        if (!dateInput.value || !timeInput.value) {
            openModal();
            showAlert('Select an available date and time to continue.');
            return;
        }

        const payload = {
            name: form.elements.name.value.trim(),
            email: form.elements.email.value.trim(),
            programname: form.elements.programname.value,
            mobilenumber: form.elements.mobilenumber.value.trim(),
            date: dateInput.value,
            time: timeInput.value,
        };

        setLoading(true);

        try {
            const order = await request('create-order.php', {
                method: 'POST',
                body: JSON.stringify(payload),
            });

            const razorpay = new window.Razorpay({
                key: order.key_id,
                amount: order.amount,
                currency: order.currency,
                name: 'Eat Rrite',
                description: 'Appointment confirmation · ₹' + amountRupees,
                order_id: order.order_id,
                prefill: {
                    name: payload.name,
                    email: payload.email,
                    contact: payload.mobilenumber,
                },
                notes: {
                    date: payload.date,
                    time: payload.time,
                    service: payload.programname,
                    email: payload.email,
                },
                theme: { color: '#38640e' },
                handler: function (response) {
                    request('verify-payment.php', {
                        method: 'POST',
                        body: JSON.stringify(response),
                    }).then(function (verified) {
                        const target = verified.redirect || 'appointment-thank-you.php';
                        window.location.assign(target);
                    }).catch(function (error) {
                        setLoading(false);
                        showAlert(error.message);
                    });
                },
                modal: {
                    ondismiss: function () {
                        setLoading(false);
                        showAlert('Payment was cancelled. Your slot is held for a few minutes if you want to try again.');
                    },
                },
            });

            razorpay.on('payment.failed', function (response) {
                setLoading(false);
                showAlert((response.error && response.error.description) || 'Payment failed. Please try again.');
            });

            razorpay.open();
        } catch (error) {
            setLoading(false);
            showAlert(error.message);
            if (/slot/i.test(error.message)) {
                state.availability = null;
                openModal();
            }
        }
    });
})();
