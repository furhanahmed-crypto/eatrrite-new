<?php

declare(strict_types=1);

/** @var string $template */
/** @var array<string, mixed> $data */

$bodyTemplate = __DIR__ . '/' . $template . '.php';
$brand = 'Eat Rrite';
$primary = '#014e4e';
$accent = '#e5b858';
$mint = '#f1f7f4';
$ink = '#1c2029';
$text = '#595b62';
$year = date('Y');
$logoSrc = $logoSrc ?? 'cid:eatrrite-logo';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($brand, ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body style="margin:0;padding:0;background:<?php echo $mint; ?>;font-family:'Bricolage Grotesque',Georgia,'Segoe UI',Arial,sans-serif;color:<?php echo $text; ?>;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:<?php echo $mint; ?>;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:20px;overflow:hidden;border:1px solid rgba(1,78,78,0.12);box-shadow:0 18px 40px rgba(1,78,78,0.08);">
                    <tr>
                        <td style="background:<?php echo $primary; ?>;padding:26px 32px;border-bottom:4px solid <?php echo $accent; ?>;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td valign="middle">
                                        <img src="<?php echo htmlspecialchars($logoSrc, ENT_QUOTES, 'UTF-8'); ?>"
                                             alt="<?php echo htmlspecialchars($brand, ENT_QUOTES, 'UTF-8'); ?>"
                                             width="168"
                                             style="display:block;width:168px;max-width:60%;height:auto;background:#ffffff;border-radius:10px;padding:8px 12px;">
                                    </td>
                                    <td valign="middle" align="right">
                                        <p style="margin:0;font-size:11px;letter-spacing:0.1em;text-transform:uppercase;color:<?php echo $accent; ?>;font-weight:700;">Nutrition &amp; Wellness</p>
                                        <p style="margin:6px 0 0;font-family:Georgia,'Times New Roman',serif;font-size:18px;line-height:1.2;color:#ffffff;font-weight:600;">Eat Healthy. Live Better.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px 32px 28px;">
                            <?php include $bodyTemplate; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:<?php echo $mint; ?>;padding:22px 32px;border-top:1px solid rgba(1,78,78,0.12);">
                            <p style="margin:0 0 8px;font-size:13px;line-height:1.55;color:<?php echo $text; ?>;">Consultations are 30 minutes. Please join Google Meet at your booked time (IST).</p>
                            <p style="margin:0;font-size:13px;color:<?php echo $primary; ?>;font-weight:600;">Phone: +91 96398 77483 · Email: info@eatrrite.com</p>
                        </td>
                    </tr>
                </table>
                <p style="margin:18px 0 0;font-size:12px;color:#819291;">&copy; <?php echo $year; ?> <?php echo htmlspecialchars($brand, ENT_QUOTES, 'UTF-8'); ?>. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
