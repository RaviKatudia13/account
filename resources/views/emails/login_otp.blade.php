<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Verification Code</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .content {
            padding: 40px 30px;
        }
        .otp-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 20px 0;
            border: 2px dashed #e9ecef;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 4px;
            margin: 20px 0;
            padding: 15px;
            background-color: white;
            border-radius: 6px;
            border: 2px solid #e9ecef;
            display: inline-block;
            min-width: 200px;
        }
        .info-section {
            background-color: #e8f4fd;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 6px 6px 0;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            font-size: 12px;
            color: #6c757d;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 0;
        }
        .btn:hover {
            background-color: #5a6fd8;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 10px;
            }
            .content {
                padding: 20px 15px;
            }
            .otp-code {
                font-size: 24px;
                letter-spacing: 2px;
                min-width: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="icon">🔐</div>
            <h1>Login Verification</h1>
            <p>Secure access to your account</p>
        </div>
        
        <div class="content">
            <h2 style="color: #333; margin-bottom: 20px;">Hello!</h2>
            
            <p style="margin-bottom: 20px; color: #555;">
                We received a login request for your account. To ensure your security, please use the verification code below:
            </p>
            
            <div class="otp-section">
                <h3 style="margin: 0 0 15px 0; color: #333;">Your Verification Code</h3>
                <div class="otp-code">{{ $code }}</div>
                <p style="margin: 10px 0 0 0; color: #666; font-size: 14px;">
                    Enter this code on the login page to complete your sign-in
                </p>
            </div>
            
            <div class="info-section">
                <h4 style="margin: 0 0 10px 0; color: #333;">📱 Code Entry Tips:</h4>
                <ul style="margin: 0; padding-left: 20px; color: #555;">
                    <li>Enter the code exactly as shown above</li>
                    <li>Each digit should be entered in separate boxes</li>
                    <li>The code is case-sensitive</li>
                </ul>
            </div>
            
            <div class="warning">
                <strong>⚠️ Security Notice:</strong>
                <ul style="margin: 10px 0 0 20px; padding: 0;">
                    <li>This code will expire in <strong>10 minutes</strong></li>
                    <li>Never share this code with anyone</li>
                    <li>If you didn't request this code, please ignore this email</li>
                </ul>
            </div>
            
            <p style="margin-top: 30px; color: #666; font-size: 14px;">
                If you're having trouble, you can request a new code from the login page.
            </p>
        </div>
        
        <div class="footer">
            <p><strong>Admin Panel</strong></p>
            <p>This is an automated security message. Please do not reply to this email.</p>
            <p>If you have any questions, contact our support team.</p>
            <p style="margin-top: 15px; font-size: 11px; color: #999;">
                © {{ date('Y') }} Admin Panel. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html> 