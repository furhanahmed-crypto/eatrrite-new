<h2 style="margin:0 0 12px;font-family:Georgia,'Times New Roman',serif;font-size:24px;line-height:1.25;color:#014e4e;">Your appointment is confirmed</h2>
<p style="margin:0 0 22px;font-size:15px;line-height:1.65;color:#595b62;">
    Hello <?php echo htmlspecialchars((string) $data['name'], ENT_QUOTES, 'UTF-8'); ?>,
    thank you for booking with Eat Rrite. Your consultation has been confirmed and payment received.
</p>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f1f7f4;border:1px solid rgba(1,78,78,0.12);border-radius:14px;">
    <tr>
        <td style="padding:20px 22px;">
            <p style="margin:0 0 10px;font-size:14px;color:#1c2029;"><strong style="color:#014e4e;">Service:</strong> <?php echo htmlspecialchars((string) $data['service'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p style="margin:0 0 10px;font-size:14px;color:#1c2029;"><strong style="color:#014e4e;">Date:</strong> <?php echo htmlspecialchars((string) $data['display_date'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p style="margin:0;font-size:14px;color:#1c2029;"><strong style="color:#014e4e;">Time:</strong> <?php echo htmlspecialchars((string) $data['display_time'], ENT_QUOTES, 'UTF-8'); ?> IST</p>
        </td>
    </tr>
</table>

<p style="margin:26px 0 14px;font-size:15px;color:#1c2029;">
    Please join your consultation using the Google Meet link below at the scheduled time.
</p>

<p style="margin:0;">
    <a href="<?php echo htmlspecialchars((string) $data['meet_link'], ENT_QUOTES, 'UTF-8'); ?>"
       style="display:inline-block;background:#e5b858;color:#1c2029;text-decoration:none;padding:14px 24px;border-radius:999px;font-weight:700;font-size:15px;">
        Join Google Meet
    </a>
</p>

<p style="margin:16px 0 0;font-size:13px;color:#819291;word-break:break-all;">
    <?php echo htmlspecialchars((string) $data['meet_link'], ENT_QUOTES, 'UTF-8'); ?>
</p>

<p style="margin:26px 0 0;font-size:14px;line-height:1.65;color:#595b62;">
    If you need to reschedule, reply to this email or contact us at +91 96398 77483.
    We look forward to supporting your wellness journey.
</p>
