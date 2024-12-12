<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
</head>

<body>
<div>
        <img src="{{ $message->embed(asset('dashboard/assets/onboardingEmail.png')) }}" alt="أهلا بك فى  Olldrop">
    للتواصل معانا <a href="https://wa.me/201023939897"> اضغط هنا </a>
    <br>
    Thanks,<br>
    {{ config('app.name') }}
</div>
</body>

</html>