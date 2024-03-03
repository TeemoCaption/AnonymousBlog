<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>驗證連結失效</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }

        .container {
            text-align: center;
            background: white;
            padding: 40px 60px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .verified-message h1 {
            color: #4CAF50;
            /* Green color */
            margin-bottom: 20px;
        }

        .verified-message p {
            color: #333;
            /* Dark grey color */
            margin-bottom: 30px;
        }

        .home-button {
            text-decoration: none;
            background-color: #4CAF50;
            /* Green color */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .home-button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="verified-message">
            <p>您的驗證連結已失效。</p>
            <p id="countdown">5秒後自動關閉此頁面...</p>
        </div>
    </div>
    <script>
        var seconds = 5; // 倒數計時的秒數
        var countdownElement = document.getElementById('countdown');

        function updateCountdown() {
            seconds--;
            countdownElement.innerHTML = seconds + "秒後自動關閉此頁面...";

            if (seconds <= 0) {
                clearInterval(countdownInterval);
                window.close(); // 倒數結束時關閉窗口
            }
        }

        // 每秒更新一次倒數計時
        var countdownInterval = setInterval(updateCountdown, 1000);
    </script>
</body>

</html>