<?php
function sendMail($to, $name, $subject, $htmlBody) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Saanidhya <noreply@saanidhya.com>" . "\r\n";
    $message = "<!DOCTYPE html><html><body>" . $htmlBody . "</body></html>";
    return mail($to, $subject, $message, $headers);
}

function emailTemplate($title, $content) {
    return "
    <div style='font-family:Inter, sans-serif; max-width:500px; margin:0 auto; border:1px solid #e0e0e0; border-radius:16px; overflow:hidden;'>
        <div style='background:#06080f; padding:20px; text-align:center;'>
            <span style='font-family:Playfair Display;font-size:28px;font-weight:700;color:#c9a84c;'>Saani<span style='color:#fff;'>dhya</span></span>
        </div>
        <div style='padding:28px 24px; background:#fff; color:#1a1a1a;'>
            <h2 style='margin-top:0; color:#c9a84c;'>{$title}</h2>
            {$content}
            <hr style='margin:24px 0; border:0; height:1px; background:#eee;'>
            <p style='font-size:12px; color:#888;'>Saanidhya – Premium Student Housing</p>
        </div>
    </div>";
}
?>