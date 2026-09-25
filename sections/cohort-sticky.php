<?php $sticky = $cohort['sticky']; ?>
<div class="co-sticky">
    <p><?php echo htmlspecialchars($sticky['text']); ?></p>
    <a class="btn btn-primary" href="<?php echo htmlspecialchars($sticky['cta']['href']); ?>" data-er-open-booking><?php echo htmlspecialchars($sticky['cta']['label']); ?></a>
</div>
