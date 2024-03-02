<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>驗證頁</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            text-align: center;
            padding: 50px;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            display: inline-block;
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        .verify-button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 25px;
            text-align: center;
            display: inline-block;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }

        .verify-button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>歡迎加入我們！</h1>
        <p>請點擊下方按鈕以完成您的電子郵件認證：</p>
        <a href="{{ route('verification.verify', ['id' => $id, 'hash' => $hash]) }}" class="verify-button">認證電子郵件</a>
    </div>

</body>

</html>