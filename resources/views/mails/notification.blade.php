<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?: '' }}</title>
    <style>
        body {
            direction: rtl;
        }
    </style>
</head>
<body>
<h1>{{ $title ?: '' }}</h1>
<p>
    {{ $content ?: '' }}
    <br>
    <a href="{{ $url }}">جزئیات</a>
</p>
<p>
    لطفا محتویات این ایمیل را در اختیار شخص دیگری قرار ندهید.
</p>
<p>
    <a href="{{ env('WEBSITE_URL') }}">{{ env('APP_NAME') }}</a>
</p>
</body>
</html>
