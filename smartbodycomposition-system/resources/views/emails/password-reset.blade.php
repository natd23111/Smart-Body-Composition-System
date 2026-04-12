<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="margin:0;padding:0;background-color:#f5f7f5;font-family:Arial,sans-serif;color:#1f2937;">
    <div style="max-width:600px;margin:0 auto;padding:32px 16px;">
        <div style="background-color:#ffffff;border:1px solid #d1fae5;border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(15,23,42,0.08);">
            <div style="padding:24px 28px;background:linear-gradient(135deg,#ecfdf5,#d1fae5);border-bottom:1px solid #a7f3d0;">
                <h1 style="margin:0;font-size:24px;line-height:1.3;color:#065f46;">Reset Your Password</h1>
                <p style="margin:8px 0 0;font-size:14px;line-height:1.6;color:#4b5563;">Smart Body Composition account security</p>
            </div>

            <div style="padding:28px;">
                <p style="margin:0 0 16px;font-size:15px;line-height:1.7;">Hi {{ $userName }},</p>
                <p style="margin:0 0 16px;font-size:15px;line-height:1.7;">We received a request to reset your password. Use the button below to choose a new password. This link will expire in 60 minutes.</p>

                <p style="margin:24px 0;">
                    <a href="{{ $resetUrl }}" style="display:inline-block;padding:12px 20px;border-radius:10px;background-color:#16a34a;color:#ffffff;text-decoration:none;font-weight:600;">Reset Password</a>
                </p>

                <p style="margin:0 0 12px;font-size:14px;line-height:1.7;color:#4b5563;">If the button does not work, copy and paste this link into your browser:</p>
                <p style="margin:0 0 16px;font-size:13px;line-height:1.7;word-break:break-all;color:#047857;">{{ $resetUrl }}</p>
                <p style="margin:0;font-size:14px;line-height:1.7;color:#6b7280;">If you did not request a password reset, you can ignore this email.</p>
            </div>
        </div>
    </div>
</body>
</html>
