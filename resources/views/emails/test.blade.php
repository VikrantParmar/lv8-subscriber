<!DOCTYPE html>
<html>
<head>
    <title>Laravel 8 Mail Send using Queue</title>
</head>
<body>

<center>
    <h2 style="padding: 23px;background: #b3deb8a1;border-bottom: 6px green solid;">
        Test Mail
    </h2>
</center>

<p>Hi, Sir</p>
<div style="border:1px solid red;border-radius:5px;padding:10px;box-size:border-box;">
    <h1>{{$details['title']}} - hoanguyenit.com<h1>
            <span style="font-size:13px">{{$details['email']}}</span>
            <p style="font-size:15px;line-height:25px;">
                {{$details['body']}}
            </p>
</div>

<strong>Thank you Sir. :)</strong>

</body>
</html>