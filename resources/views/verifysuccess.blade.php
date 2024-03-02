<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>驗證成功</title>
    <link rel="stylesheet" href="style.css"> <!-- 引入CSS樣式 -->

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4CAF50;
            /* 綠色 */
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 30px;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>註冊成功！</h1>
        <p>您的帳號已經成功驗證，現在可以登入帳號開始使用我們的服務了。</p>
        <p id="countdown">5秒後自動關閉此頁面...</p>
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