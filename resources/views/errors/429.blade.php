<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>429 - Bitte kurz warten</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f9fafb;
            color: #374151;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            text-align: center;
            max-width: 500px;
        }
        .icon {
            width: 96px;
            height: 96px;
            margin: 0 auto 32px;
            color: #f59e0b;
        }
        h1 {
            font-size: 48px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 16px;
        }
        h2 {
            font-size: 24px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 16px;
        }
        p {
            color: #6b7280;
            margin-bottom: 24px;
            line-height: 1.6;
        }
        .info-box {
            background-color: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 32px;
        }
        .info-box p {
            color: #92400e;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .info-box p:last-child {
            margin-bottom: 0;
        }
        .buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-primary {
            background-color: #4a90a4;
            color: white;
        }
        .btn-primary:hover {
            background-color: #3a7a94;
        }
        .btn-secondary {
            background-color: #e5e7eb;
            color: #374151;
        }
        .btn-secondary:hover {
            background-color: #d1d5db;
        }
        .btn svg {
            width: 16px;
            height: 16px;
            margin-right: 8px;
        }
        .timer {
            font-size: 18px;
            font-weight: 600;
            color: #f59e0b;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>

        <h1>Moment bitte!</h1>
        <h2>Zu viele Anmeldeversuche</h2>

        <p>Aus Sicherheitsgründen müssen Sie kurz warten, bevor Sie sich erneut anmelden können.</p>

        <div class="info-box">
            <p><strong>Warum sehen Sie diese Meldung?</strong></p>
            <p>Nach mehreren Anmeldeversuchen wird der Zugang kurz gesperrt, um Ihre Daten zu schützen.</p>
            <p>Sie können es in <strong>einer Minute</strong> wieder versuchen.</p>
        </div>

        <div class="timer" id="countdown">Bitte warten Sie noch <span id="seconds">60</span> Sekunden...</div>

        <div class="buttons">
            <a href="/pinnwand" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Zur Pinnwand
            </a>

            <button onclick="history.back()" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Zurück
            </button>
        </div>
    </div>

    <script>
        // Countdown timer
        let seconds = 60;
        const countdownElement = document.getElementById('seconds');
        const timerElement = document.getElementById('countdown');

        const timer = setInterval(() => {
            seconds--;
            if (seconds > 0) {
                countdownElement.textContent = seconds;
            } else {
                clearInterval(timer);
                timerElement.innerHTML = '✓ Sie können es jetzt wieder versuchen!';
                timerElement.style.color = '#10b981';
            }
        }, 1000);
    </script>
</body>
</html>