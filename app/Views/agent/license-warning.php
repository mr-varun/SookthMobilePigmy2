<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Warning - Agent Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .container-wrapper {
            width: 100%;
            max-width: 600px;
        }

        .warning-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .warning-header {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .warning-icon {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            backdrop-filter: blur(10px);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(255, 255, 255, 0);
            }
        }

        .warning-icon i {
            font-size: 3rem;
        }

        .warning-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .warning-subtitle {
            font-size: 1rem;
            opacity: 0.9;
        }

        .warning-body {
            padding: 2rem 1.5rem;
        }

        .expiry-info {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            border: 3px solid #fbbf24;
        }

        .days-remaining {
            font-size: 4rem;
            font-weight: 800;
            color: #f59e0b;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .days-label {
            font-size: 1.2rem;
            color: #92400e;
            font-weight: 600;
        }

        .expiry-date {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .expiry-date i {
            color: #f59e0b;
            font-size: 1.2rem;
        }

        .expiry-date span {
            font-weight: 600;
            color: #374151;
        }

        .warning-message {
            background: #f3f4f6;
            border-left: 4px solid #f59e0b;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .warning-message .message-title {
            font-weight: 700;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .warning-message .message-text {
            color: #6b7280;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .action-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .btn-continue {
            width: 100%;
            padding: 1.25rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-continue:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }

        .contact-info {
            text-align: center;
            padding: 1.5rem;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }

        .contact-info .title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 1rem;
        }

        .contact-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-contact {
            flex: 1;
            max-width: 200px;
            padding: 0.75rem;
            border-radius: 10px;
            border: none;
            color: white;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-phone {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .btn-phone:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
            color: white;
        }

        .btn-whatsapp {
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
        }

        .btn-whatsapp:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(37, 211, 102, 0.3);
            color: white;
        }

        @media (max-width: 576px) {
            body {
                padding: 0.5rem;
            }

            .warning-header {
                padding: 1.5rem 1rem;
            }

            .warning-icon {
                width: 80px;
                height: 80px;
            }

            .warning-icon i {
                font-size: 2.5rem;
            }

            .warning-title {
                font-size: 1.75rem;
            }

            .warning-body {
                padding: 1.5rem 1rem;
            }

            .days-remaining {
                font-size: 3rem;
            }

            .expiry-info {
                padding: 1.5rem;
            }

            .contact-buttons {
                flex-direction: column;
            }

            .btn-contact {
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="container-wrapper">
        <div class="warning-container">
            <!-- Header -->
            <div class="warning-header">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h1 class="warning-title">License Expiring Soon!</h1>
                <p class="warning-subtitle">Your branch license needs renewal</p>
            </div>

            <!-- Body -->
            <div class="warning-body">
                <!-- Expiry Info -->
                <div class="expiry-info">
                    <div class="days-remaining"><?= $days_until_expiry ?></div>
                    <div class="days-label">
                        <?= $days_until_expiry == 1 ? 'Day' : 'Days' ?> Remaining
                    </div>
                    <div class="expiry-date">
                        <i class="fas fa-calendar-times"></i>
                        <span>Expires on: <?= date('d M Y', strtotime($license_expiry_date)) ?></span>
                    </div>
                </div>

                <!-- Warning Message -->
                <div class="warning-message">
                    <div class="message-title">
                        <i class="fas fa-info-circle"></i> Important Notice
                    </div>
                    <div class="message-text">
                        Your branch license is expiring soon. Please contact your administrator to renew the license before it expires. 
                        Once expired, you will not be able to perform any transactions until the license is renewed.
                    </div>
                </div>

                <!-- Action -->
                <form method="POST" action="<?= url('agent/license-warning') ?>">
                    <div class="action-section">
                        <button type="submit" class="btn-continue">
                            <i class="fas fa-check-circle"></i>
                            <span>Continue to Dashboard</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="contact-info">
                <div class="title">
                    <i class="fas fa-phone-volume"></i> Need Help? Contact Admin
                </div>
                <div class="contact-buttons">
                    <a href="tel:+919876543210" class="btn-contact btn-phone">
                        <i class="fas fa-phone"></i>
                        <span>Call Admin</span>
                    </a>
                    <a href="https://wa.me/919876543210?text=Hi, I need to renew my branch license" 
                       class="btn-contact btn-whatsapp" 
                       target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        <span>WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-update remaining time if needed
        setInterval(() => {
            // Optional: Add real-time countdown if needed
        }, 1000);
    </script>
</body>
</html>
