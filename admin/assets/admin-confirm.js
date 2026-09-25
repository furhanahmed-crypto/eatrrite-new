(function () {
    'use strict';

    const root = document.querySelector('[data-er-confirm]');
    if (!root) {
        window.erConfirm = function () { return Promise.resolve(window.confirm('Continue?')); };
        return;
    }

    const titleEl = root.querySelector('[data-er-confirm-title]');
    const copyEl = root.querySelector('[data-er-confirm-copy]');
    const cancelEl = root.querySelector('[data-er-confirm-cancel]');
    const okEl = root.querySelector('[data-er-confirm-ok]');
    let settle = null;

    function close(ok) {
        root.hidden = true;
        document.body.style.overflow = '';
        if (settle) {
            const done = settle;
            settle = null;
            done(ok);
        }
    }

    window.erConfirm = function (opts) {
        const options = opts || {};
        titleEl.textContent = options.title || 'Are you sure?';
        copyEl.textContent = options.description || '';
        cancelEl.textContent = options.cancelLabel || 'Cancel';
        okEl.textContent = options.confirmLabel || 'Confirm';
        okEl.classList.toggle('is-danger', Boolean(options.danger));
        root.hidden = false;
        document.body.style.overflow = 'hidden';
        return new Promise(function (resolve) { settle = resolve; });
    };

    cancelEl.addEventListener('click', function () { close(false); });
    root.querySelector('[data-er-confirm-dismiss]').addEventListener('click', function () { close(false); });
    okEl.addEventListener('click', function () { close(true); });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !root.hidden) {
            close(false);
        }
    });

    document.querySelectorAll('[data-er-confirm-form]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (form.getAttribute('data-er-confirmed') === '1') {
                return;
            }
            event.preventDefault();
            window.erConfirm({
                title: form.getAttribute('data-title') || 'Are you sure?',
                description: form.getAttribute('data-description') || '',
                confirmLabel: form.getAttribute('data-confirm') || 'Confirm',
                cancelLabel: form.getAttribute('data-cancel') || 'Cancel',
                danger: form.getAttribute('data-danger') === '1',
            }).then(function (ok) {
                if (!ok) {
                    return;
                }
                form.setAttribute('data-er-confirmed', '1');
                form.submit();
            });
        });
    });
})();
