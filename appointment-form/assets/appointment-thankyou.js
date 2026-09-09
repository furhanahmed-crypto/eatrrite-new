(function () {
    'use strict';

    const root = document.getElementById('er-appointment-thankyou');
    const dataEl = document.getElementById('er-booking-data');
    if (!root || !dataEl) {
        return;
    }

    const successBox = root.querySelector('[data-er-success]');
    const apiBase = root.dataset.api;
    const csrf = root.dataset.csrf;

    let bookingData;
    try {
        bookingData = JSON.parse(dataEl.textContent || '{}');
    } catch (error) {
        return;
    }

    const verified = bookingData.verified || {};
    const paymentResponse = bookingData.payment || {};

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

    function showMeetLinkPending() {
        successBox.querySelector('[data-er-meet-pending]').hidden = false;
        successBox.querySelector('[data-er-meet-link]').hidden = true;
        successBox.querySelector('[data-er-meet-error]').hidden = true;
    }

    function showMeetLink(url) {
        const meet = successBox.querySelector('[data-er-meet-link]');
        successBox.querySelector('[data-er-meet-pending]').hidden = true;
        successBox.querySelector('[data-er-meet-error]').hidden = true;
        meet.hidden = false;
        meet.href = url;
        meet.textContent = url;
    }

    function showMeetLinkError(message) {
        successBox.querySelector('[data-er-meet-pending]').hidden = true;
        successBox.querySelector('[data-er-meet-link]').hidden = true;
        successBox.querySelector('[data-er-meet-error]').hidden = false;
        successBox.querySelector('[data-er-meet-error]').textContent = message;
    }

    function updateEmailHint(result) {
        const hint = successBox.querySelector('[data-er-email-hint]');
        if (!hint) {
            return;
        }

        if (result.emails_sent) {
            hint.textContent = 'We also emailed your confirmation and Google Meet link to ' +
                (result.email || verified.email || 'your inbox') + '.';
            return;
        }

        if (result.meet_link || result.meet_link_ready) {
            hint.textContent = 'Save the Google Meet link above. If you do not receive the confirmation email shortly, contact us.';
            return;
        }

        hint.textContent = 'We will email your confirmation once your Meet link is ready.';
    }

    function renderSuccess(result) {
        successBox.querySelector('[data-er-success-copy]').textContent =
            'Hi ' + result.name + ', your ' + result.service + ' consultation is booked for ' +
            result.display_date + ' at ' + result.display_time + '.';
        updateEmailHint(result);

        if (result.meet_link_ready && result.meet_link) {
            showMeetLink(result.meet_link);
        } else {
            showMeetLinkPending();
        }
    }

    function sleep(ms) {
        return new Promise(function (resolve) {
            setTimeout(resolve, ms);
        });
    }

    async function finalizeMeetLink() {
        for (let attempt = 0; attempt < 60; attempt += 1) {
            const result = await request('finalize-booking.php', {
                method: 'POST',
                body: JSON.stringify(paymentResponse),
            });

            if (result.status === 'completed' && result.meet_link) {
                showMeetLink(result.meet_link);
                updateEmailHint(Object.assign({}, verified, result));
                return;
            }

            if (result.status === 'processing') {
                await sleep(3000);
                continue;
            }

            throw new Error('Meet link could not be generated.');
        }

        throw new Error('Meet link is taking longer than expected. Your payment was received — please refresh this page in a minute or contact us.');
    }

    renderSuccess(verified);

    if (!verified.meet_link_ready) {
        finalizeMeetLink().catch(function (error) {
            showMeetLinkError(error.message + ' Your payment was received — we will follow up shortly.');
        });
    }
})();
