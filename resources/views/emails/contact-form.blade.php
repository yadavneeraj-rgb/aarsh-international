<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
        }

        .content {
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .field {
            margin-bottom: 15px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .value {
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>New Contact Form Submission</h2>
            <p>Aarsh International - Global Foodstuff Trading</p>
        </div>

        <div class="content">
            <h3>Contact Details:</h3>

            <div class="field">
                <span class="label">First Name:</span>
                <span class="value">{{ $data['first_name'] }}</span>
            </div>

            <div class="field">
                <span class="label">Last Name:</span>
                <span class="value">{{ $data['last_name'] }}</span>
            </div>

            <div class="field">
                <span class="label">Email Address:</span>
                <span class="value">{{ $data['email'] }}</span>
            </div>

            <div class="field">
                <span class="label">Phone Number:</span>
                <span class="value">{{ $data['phone'] }}</span>
            </div>

            <div class="field">
                <span class="label">Comment/Message:</span>
                <div class="value" style="margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 5px;">
                    {{ $data['comment'] }}
                </div>
            </div>

            <div class="field">
                <span class="label">Submitted At:</span>
                <span class="value">{{ now()->format('F j, Y \a\t g:i A') }}</span>
            </div>
        </div>
    </div>
</body>

</html>