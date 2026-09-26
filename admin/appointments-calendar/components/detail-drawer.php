<div class="er-cal-drawer" data-er-drawer hidden>
    <div class="er-cal-drawer__backdrop" data-er-drawer-close></div>
    <aside class="er-cal-drawer__panel" role="dialog" aria-modal="true" aria-labelledby="er-cal-drawer-title">
        <header class="er-cal-drawer__head">
            <p class="er-cal-drawer__kicker">Consultation</p>
            <h2 id="er-cal-drawer-title" data-er-drawer-name></h2>
            <p class="er-cal-drawer__service" data-er-drawer-service></p>
            <button type="button" class="er-cal-drawer__close" data-er-drawer-close aria-label="Close">Close</button>
        </header>
        <div class="er-cal-drawer__body">
            <div class="er-cal-drawer__when">
                <div>
                    <span>Date</span>
                    <strong data-er-drawer-date></strong>
                </div>
                <div>
                    <span>Time</span>
                    <strong data-er-drawer-time></strong>
                </div>
            </div>
            <dl class="er-cal-drawer__meta">
                <div>
                    <dt>Phone</dt>
                    <dd data-er-drawer-phone></dd>
                </div>
                <div>
                    <dt>Email</dt>
                    <dd data-er-drawer-email></dd>
                </div>
                <div>
                    <dt>Google Meet</dt>
                    <dd data-er-drawer-meet></dd>
                </div>
            </dl>
            <section class="er-cal-drawer__answers" data-er-drawer-answers aria-label="Questionnaire"></section>
            <p class="er-admin-alert" data-er-drawer-error hidden></p>
        </div>
        <footer class="er-cal-drawer__foot">
            <button type="button" class="er-cal-drawer__cancel" data-er-cancel-booking>Cancel booking</button>
        </footer>
    </aside>
</div>
