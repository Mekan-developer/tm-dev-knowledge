<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>DevKnowledge Contact</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827;">
    <h2 style="margin: 0 0 12px;">New message from DevKnowledge</h2>
    <p><strong>Name:</strong> {{ $payload['name'] }}</p>
    <p><strong>Email:</strong> {{ $payload['email'] }}</p>
    <p><strong>Subject:</strong> {{ $payload['subject'] }}</p>
    <p><strong>Message:</strong></p>
    <div style="white-space: pre-wrap; padding: 12px; background: #f3f4f6; border-radius: 6px;">
        {{ $payload['message'] }}
    </div>
    <p style="margin-top: 16px; color: #6b7280;"><strong>Sent at:</strong> {{ $sentAt }}</p>
</body>
</html>
