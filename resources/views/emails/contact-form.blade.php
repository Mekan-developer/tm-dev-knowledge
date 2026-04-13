<!doctype html>
<html lang="tk">
<head>
    <meta charset="utf-8">
    <title>DevKnowledge habarlaşyk</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827;">
    <h2 style="margin: 0 0 12px;">DevKnowledge-dan täze hat</h2>
    <p><strong>At:</strong> {{ $payload['name'] }}</p>
    <p><strong>E-poçta:</strong> {{ $payload['email'] }}</p>
    <p><strong>Mowzuk:</strong> {{ $payload['subject'] }}</p>
    <p><strong>Hat:</strong></p>
    <div style="white-space: pre-wrap; padding: 12px; background: #f3f4f6; border-radius: 6px;">
        {{ $payload['message'] }}
    </div>
    <p style="margin-top: 16px; color: #6b7280;"><strong>Iberilen wagty:</strong> {{ $sentAt }}</p>
</body>
</html>
