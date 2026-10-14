<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verification Code</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 24px; color: #1e293b;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 520px; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <tr>
            <td align="center" style="padding-bottom: 20px;">
                <h2 style="margin: 0; color: #4f46e5; font-size: 24px; font-weight: bold;">MailCraft</h2>
            </td>
        </tr>
        <tr>
            <td>
                <h3 style="margin: 0 0 12px 0; color: #0f172a; font-size: 18px;">
                    @if($purpose === 'signup') Confirm your Email Address @else Verify Password Change @endif
                </h3>
                <p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #475569;">
                    Use the 6-digit verification code below to complete your request. This code will expire in 15 minutes.
                </p>
                <div style="background-color: #f1f5f9; border-radius: 8px; padding: 18px; text-align: center; margin-bottom: 24px;">
                    <span style="font-family: monospace; font-size: 32px; font-weight: bold; letter-spacing: 6px; color: #4f46e5;">{{ $code }}</span>
                </div>
                <p style="margin: 0; font-size: 12px; color: #94a3b8; line-height: 1.5;">
                    If you did not request this verification code, please ignore this email.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
