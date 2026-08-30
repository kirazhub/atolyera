<?php
/** SMTP e-posta gönderimi (PHPMailer) */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../lib/PHPMailer/Exception.php';
require_once __DIR__ . '/../lib/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/SMTP.php';

/**
 * E-posta gönderir. SMTP kapalıysa (config) sessizce false döner (site çökmez).
 * @return bool başarılı mı
 */
function send_mail(string $to, string $subject, string $htmlBody, ?string $toName = null): bool {
    if (!config('smtp.enabled')) {
        // SMTP henüz ayarlı değil — siparişi kaydetmeye devam et, maili atlarız.
        error_log("[Atölye RA] SMTP kapalı, mail atlanmadı: $to / $subject");
        return false;
    }
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host       = config('smtp.host');
        $mail->SMTPAuth   = true;
        $mail->Username   = config('smtp.username');
        $mail->Password   = config('smtp.password');
        $mail->SMTPSecure = config('smtp.secure') === 'ssl'
            ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int)config('smtp.port');

        $mail->setFrom(config('smtp.from'), config('smtp.from_name'));
        $mail->addAddress($to, $toName ?: $to);
        $mail->addReplyTo(config('company.email'), config('site_name'));

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = trim(strip_tags(str_replace(['<br>', '</p>'], "\n", $htmlBody)));
        $mail->send();
        return true;
    } catch (Exception $ex) {
        error_log('[Atölye RA] Mail hatası: ' . $mail->ErrorInfo);
        return false;
    }
}

/** Sipariş onay e-postası (müşteriye) — marka tonunda HTML */
function order_email_html(array $order, array $items): string {
    $rows = '';
    foreach ($items as $it) {
        $rows .= '<tr>'
            . '<td style="padding:10px 0;border-bottom:1px solid #eee;">'
            . '<strong>' . e($it['name']) . '</strong><br>'
            . '<span style="color:#777;font-size:13px;">' . e($it['no_label']) . ' · ' . e($it['size']) . ' × ' . (int)$it['qty'] . '</span></td>'
            . '<td style="padding:10px 0;border-bottom:1px solid #eee;text-align:right;white-space:nowrap;">' . money($it['line_total']) . '</td>'
            . '</tr>';
    }
    $c = config('company');
    return '<div style="font-family:Georgia,\'Times New Roman\',serif;max-width:560px;margin:0 auto;color:#1A1A1A;background:#F5F1EA;padding:32px;">'
        . '<h1 style="font-size:24px;letter-spacing:.02em;font-weight:400;margin:0 0 4px;">Atölye RA</h1>'
        . '<p style="color:#5C1A1B;letter-spacing:.14em;text-transform:uppercase;font-size:11px;margin:0 0 24px;">Siparişiniz Alındı</p>'
        . '<p>Değerli ' . e($order['customer_name']) . ',</p>'
        . '<p>Seçtiğiniz parça(lar) için teşekkür ederiz. Siparişiniz alınmıştır; en kısa sürede sizinle iletişime geçeceğiz.</p>'
        . '<p style="margin:20px 0 6px;"><strong>Sipariş No:</strong> ' . e($order['order_code']) . '</p>'
        . '<table style="width:100%;border-collapse:collapse;margin:12px 0;">' . $rows
        . '<tr><td style="padding:12px 0 0;text-align:right;" colspan="2"><strong>Toplam: ' . money($order['total']) . '</strong></td></tr>'
        . '</table>'
        . '<p style="margin-top:8px;"><strong>Teslimat:</strong><br>' . nl2br(e($order['address'])) . '<br>' . e($order['city']) . '</p>'
        . '<hr style="border:none;border-top:1px solid #d8d2c6;margin:24px 0;">'
        . '<p style="font-size:13px;color:#555;">Sorularınız için: <a href="mailto:' . e($c['email']) . '" style="color:#5C1A1B;">' . e($c['email']) . '</a></p>'
        . '<p style="font-size:12px;color:#999;">Aynısı bir daha doğmaz. — Atölye RA</p>'
        . '</div>';
}

/** Sipariş bildirimi (mağazaya) */
function order_notify_html(array $order, array $items): string {
    $lines = '';
    foreach ($items as $it) $lines .= '• ' . e($it['name']) . ' (' . e($it['no_label']) . ') × ' . (int)$it['qty'] . ' — ' . money($it['line_total']) . '<br>';
    return '<div style="font-family:Arial,sans-serif;color:#111;">'
        . '<h2>Yeni Sipariş — ' . e($order['order_code']) . '</h2>'
        . '<p><strong>Müşteri:</strong> ' . e($order['customer_name']) . '<br>'
        . '<strong>E-posta:</strong> ' . e($order['email']) . '<br>'
        . '<strong>Telefon:</strong> ' . e($order['phone']) . '<br>'
        . '<strong>Adres:</strong> ' . nl2br(e($order['address'])) . ' / ' . e($order['city']) . '</p>'
        . '<p>' . $lines . '</p>'
        . '<p><strong>Toplam: ' . money($order['total']) . '</strong></p>'
        . ($order['note'] ? '<p><strong>Not:</strong> ' . nl2br(e($order['note'])) . '</p>' : '')
        . '</div>';
}
