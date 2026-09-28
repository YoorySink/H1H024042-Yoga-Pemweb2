<!DOCTYPE html>
<html>
<head>
    <title>Test Blade Escaping</title>
</head>
<body>

@php
    $cari = "<script>alert('hack')</script>";
@endphp

<h4>Escaped</h4>
<p>{{ $cari }}</p>

<h4>Raw / Unescaped</h4>
<p>{!! $cari !!}</p>

</body>
</html>