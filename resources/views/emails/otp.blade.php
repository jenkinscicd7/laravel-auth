<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset Code</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f9fafb; padding: 20px;">
    <div style="max-width: 500px; margin: auto; background: #ffffff; border-radius: 10px; padding: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
        <h2 style="color: #4f46e5; text-align: center;">Password Reset Request</h2>
        <p style="font-size: 16px; color: #333; text-align: center;">
            Use the OTP below to reset your password. This code will expire in <strong>10 minutes</strong>.
        </p>

        <div style="font-size: 24px; font-weight: bold; text-align: center; margin: 20px 0; letter-spacing: 8px; color: #111;">
            {{ $otp }}
        </div>

        <p style="font-size: 14px; color: #666; text-align: center;">
            If you did not request this password reset, please ignore this email.
        </p>
    </div>
</body>
</html>
