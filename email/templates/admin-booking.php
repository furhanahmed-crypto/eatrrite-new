<h2 style="margin:0 0 12px;font-family:Georgia,'Times New Roman',serif;font-size:24px;line-height:1.25;color:#014e4e;">New paid appointment booked</h2>
<p style="margin:0 0 24px;font-size:15px;line-height:1.65;color:#595b62;">
    A client has completed payment and confirmed an online consultation slot.
</p>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f1f7f4;border:1px solid rgba(1,78,78,0.12);border-radius:14px;">
    <tr>
        <td style="padding:20px 22px;">
            <p style="margin:0 0 10px;font-size:14px;color:#1c2029;"><strong style="color:#014e4e;">Name:</strong> <?php echo htmlspecialchars((string) $data['name'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p style="margin:0 0 10px;font-size:14px;color:#1c2029;"><strong style="color:#014e4e;">Email:</strong> <?php echo htmlspecialchars((string) $data['email'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p style="margin:0 0 10px;font-size:14px;color:#1c2029;"><strong style="color:#014e4e;">Phone:</strong> <?php echo htmlspecialchars((string) $data['phone'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p style="margin:0 0 10px;font-size:14px;color:#1c2029;"><strong style="color:#014e4e;">Service:</strong> <?php echo htmlspecialchars((string) $data['service'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p style="margin:0 0 10px;font-size:14px;color:#1c2029;"><strong style="color:#014e4e;">Date:</strong> <?php echo htmlspecialchars((string) $data['display_date'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p style="margin:0 0 10px;font-size:14px;color:#1c2029;"><strong style="color:#014e4e;">Time:</strong> <?php echo htmlspecialchars((string) $data['display_time'], ENT_QUOTES, 'UTF-8'); ?></p>
            <?php if (!empty($data['payment_id'])): ?>
                <p style="margin:0 0 10px;font-size:14px;color:#1c2029;"><strong style="color:#014e4e;">Payment ID:</strong> <?php echo htmlspecialchars((string) $data['payment_id'], ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
            <?php if (!empty($data['booked_at'])): ?>
                <p style="margin:0;font-size:14px;color:#1c2029;"><strong style="color:#014e4e;">Booked at:</strong> <?php echo htmlspecialchars((string) $data['booked_at'], ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
        </td>
    </tr>
</table>

<?php if (!empty($data['meet_link'])): ?>
    <p style="margin:26px 0 12px;font-size:15px;color:#1c2029;"><strong>Google Meet link</strong></p>
    <p style="margin:0;">
        <a href="<?php echo htmlspecialchars((string) $data['meet_link'], ENT_QUOTES, 'UTF-8'); ?>"
           style="display:inline-block;background:#e5b858;color:#1c2029;text-decoration:none;padding:12px 22px;border-radius:999px;font-weight:700;font-size:14px;">
            Open Google Meet
        </a>
    </p>
    <p style="margin:12px 0 0;font-size:13px;color:#819291;word-break:break-all;">
        <?php echo htmlspecialchars((string) $data['meet_link'], ENT_QUOTES, 'UTF-8'); ?>
    </p>
<?php endif; ?>
