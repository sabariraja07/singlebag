<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? "title" }}</title>
</head>
<body>
    <p>{{ $description ?? "description" }}</p>

    <br>

    <p>Put your text here.</p>

    <p>Place your dynamic content here.</p>

    <br>

    <p style="text-align: center;">{!! $footer ?? "" !!}</p>
</body>
</html>
