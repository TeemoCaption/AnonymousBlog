<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>匿名交友</title>
    @vite('resources/css/app.css')
</head>

<body>
    <div id="login-app">
        
    </div>
    @vite('resources/js/login.js')
</body>



</html>