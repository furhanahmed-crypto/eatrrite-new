<div class="er-modal" id="er-slot-modal" hidden>
    <div class="er-modal__backdrop" data-er-close-slots></div>
    <div class="er-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="er-slot-title">
        <header class="er-modal__header">
            <div>
                <p class="er-modal__eyebrow">Choose a slot</p>
                <h2 id="er-slot-title">Date and time</h2>
            </div>
            <button type="button" class="er-icon-btn" data-er-close-slots aria-label="Close">×</button>
        </header>

        <div class="er-calendar">
            <div class="er-calendar__nav">
                <button type="button" class="er-icon-btn" data-er-prev-month aria-label="Previous month">‹</button>
                <h3 data-er-month-label></h3>
                <button type="button" class="er-icon-btn" data-er-next-month aria-label="Next month">›</button>
            </div>
            <div class="er-weekdays">
                <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
            </div>
            <div class="er-days" data-er-days></div>
        </div>

        <div class="er-times">
            <div class="er-times__head">
                <strong data-er-selected-day>Select a date</strong>
                <span data-er-times-meta></span>
            </div>
            <div class="er-times__grid" data-er-times></div>
        </div>

        <footer class="er-modal__footer">
            <button type="button" class="er-btn-ghost" data-er-close-slots>Cancel</button>
            <button type="button" class="er-btn-primary" data-er-confirm-slot disabled>Confirm slot</button>
        </footer>
    </div>
</div>
