{{-- resources/views/emails/reset_password.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <p>Hello {{$userName}},</p>
    <p>We received a request to reset your password. Click the link below to set a new password:</p>
    <a href="{{ $resetLink }}">{{ $resetLink }}</a>
    <p>If you did not request a password reset, please ignore this email.</p>
    <p>Thank you!</p>
    <p>Best regards,</p>
    <p>The Swimcapz Team</p>

</body>
</html>
