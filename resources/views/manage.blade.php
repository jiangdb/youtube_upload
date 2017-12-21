<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link href="https://cdn.bootcss.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 square text-center"><a href="{{ route('task.index') }}">VIDEO TOOLS</a></div>
        <div class="col-md-6 square text-center"><a href="{{ route('genfile.index') }}">GENCSV</a></div>
    </div>
    <div class="row">
        <div class="col-md-6 square text-center"></div>
        <div class="col-md-6 square text-center"></div>
    </div>
</div>
</body>
<style type="text/css">
    body {
        padding-top: 120px;
    }
    .row {
        margin-bottom: 30px;
    }
    .square a {
        display: block;
        width: 100%;
        line-height: 300px;
        background-color: #ccc;
        font-size: 68px;
    }
    a:hover, a:active {
        text-decoration: none;
    }
</style>
</html>