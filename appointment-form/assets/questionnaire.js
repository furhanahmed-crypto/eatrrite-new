(function () {
    'use strict';

    const root = document.getElementById('er-q-app');
    if (!root) {
        return;
    }

    const form = document.getElementById('er-q-form');
    const steps = form ? Array.from(form.querySelectorAll('[data-er-q-step]')) : [];
    const alertBox = form ? form.querySelector('[data-er-q-alert]') : null;
    const appointmentId = root.dataset.appointmentId;
    let index = 0;

    function request(path, body) {
        return fetch(root.dataset.api + '/' + path, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': root.dataset.csrf,
            },
            body: JSON.stringify(body),
        }).then(function (response) {
            return response.json().catch(function () {
                return { ok: false, error: 'Unexpected server response.' };
            }).then(function (payload) {
                if (!response.ok || !payload.ok) {
                    throw new Error(payload.error || 'Something went wrong.');
                }
                return payload;
            });
        });
    }

    function showAlert(message) {
        if (!alertBox) {
            return;
        }
        alertBox.hidden = !message;
        alertBox.textContent = message || '';
    }

    function currentValue(step) {
        const text = step.querySelector('input.er-q-input');
        if (text) {
            return text.value.trim();
        }
        const checked = Array.from(step.querySelectorAll('input:checked')).map(function (el) {
            return el.value;
        });
        if (step.querySelector('input[type="checkbox"]')) {
            return checked;
        }
        return checked[0] || '';
    }

    function validStep(step) {
        const input = step.querySelector('input.er-q-input');
        const value = currentValue(step);
        if (input && input.type === 'number' && value !== '') {
            const age = Number(value);
            if (age < Number(input.min || 13) || age > Number(input.max || 90)) {
                return 'Please enter an age between ' + input.min + ' and ' + input.max + '.';
            }
        }
        if (step.querySelector('[required], input[type="radio"], input[type="checkbox"]')) {
            const empty = Array.isArray(value) ? value.length === 0 : value === '';
            if (empty && (step.querySelector('[required]') || step.querySelector('input[type="radio"], input[type="checkbox"]'))) {
                return 'Please answer this question to continue.';
            }
        }
        return '';
    }

    function render() {
        steps.forEach(function (step, i) {
            step.hidden = i !== index;
        });
        form.querySelector('[data-er-q-prev]').hidden = index === 0;
        form.querySelector('[data-er-q-next]').hidden = index === steps.length - 1;
        form.querySelector('[data-er-q-submit]').hidden = index !== steps.length - 1;
        form.querySelector('[data-er-q-label]').textContent = 'Question ' + (index + 1) + ' of ' + steps.length;
        form.querySelector('[data-er-q-remain]').textContent = (steps.length - index - 1) + ' remaining';
        const fill = steps.length < 2 ? 100 : (index / (steps.length - 1)) * 100;
        form.querySelector('[data-er-q-bar]').style.width = fill + '%';
        form.querySelector('[role="progressbar"]').setAttribute('aria-valuenow', String(index + 1));
        form.querySelectorAll('[data-er-q-dot]').forEach(function (dot) {
            const i = Number(dot.getAttribute('data-er-q-dot'));
            dot.classList.toggle('is-current', i === index);
            dot.classList.toggle('is-done', i < index);
        });
    }

    function collectAnswers() {
        const answers = {};
        steps.forEach(function (step) {
            const named = step.querySelector('[name]');
            if (!named) {
                return;
            }
            const key = named.name.replace(/\[\]$/, '');
            answers[key] = currentValue(step);
            const follow = step.querySelector('textarea[name]');
            if (follow) {
                answers[follow.name] = follow.value.trim();
            }
        });
        return answers;
    }

    function setLeft(message) {
        const left = document.querySelector('[data-er-q-left]');
        if (left) {
            left.textContent = message;
        }
    }

    function showDone() {
        if (form) {
            form.hidden = true;
        }
        root.querySelector('[data-er-q-done]').hidden = false;
        setLeft('Thank you. We have your responses and are generating your Google Meet link.');
    }

    function showMeet(url) {
        const link = root.querySelector('[data-er-meet-link]');
        root.querySelector('[data-er-meet-pending]').hidden = true;
        root.querySelector('[data-er-meet-error]').hidden = true;
        link.hidden = false;
        link.href = url;
        link.textContent = url;
        root.querySelector('[data-er-email-hint]').textContent =
            'A confirmation email with your appointment details and Meet link is on its way.';
        setLeft('Your questionnaire is in and your Google Meet link is ready. We have also emailed the details to you.');
    }

    function showMeetError(message) {
        root.querySelector('[data-er-meet-pending]').hidden = true;
        root.querySelector('[data-er-meet-error]').hidden = false;
        root.querySelector('[data-er-meet-error]').textContent = message;
    }

    function sleep(ms) {
        return new Promise(function (resolve) {
            setTimeout(resolve, ms);
        });
    }

    function generateMeet() {
        showDone();
        root.querySelector('[data-er-meet-pending]').hidden = false;
        return (function poll(attempt) {
            return request('generate-meet.php', { appointment_id: appointmentId }).then(function (result) {
                if (result.meet_link) {
                    showMeet(result.meet_link);
                    return;
                }
                if (result.status === 'processing' && attempt < 60) {
                    return sleep(3000).then(function () {
                        return poll(attempt + 1);
                    });
                }
                throw new Error('Meet link could not be generated.');
            });
        })(0).catch(function () {
            showMeetError('A technical issue occurred while preparing your Meet link. Your appointment is saved — please refresh or contact us.');
        });
    }

    if (root.dataset.meetLink) {
        showDone();
        showMeet(root.dataset.meetLink);
        return;
    }

    if (root.dataset.done === '1' || !form) {
        generateMeet();
        return;
    }

    form.querySelector('[data-er-q-next]').addEventListener('click', function () {
        const error = validStep(steps[index]);
        showAlert(error);
        if (error) {
            return;
        }
        index += 1;
        render();
    });
    form.querySelector('[data-er-q-prev]').addEventListener('click', function () {
        showAlert('');
        index -= 1;
        render();
    });
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const error = validStep(steps[index]);
        showAlert(error);
        if (error) {
            return;
        }
        form.querySelector('[data-er-q-submit]').disabled = true;
        request('submit-questionnaire.php', {
            appointment_id: appointmentId,
            answers: collectAnswers(),
        }).then(generateMeet).catch(function (err) {
            form.querySelector('[data-er-q-submit]').disabled = false;
            showAlert(err.message || 'Please check your answers and try again.');
        });
    });
    render();
})();
