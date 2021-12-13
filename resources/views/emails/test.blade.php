<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
  <div class="container" style="font-size:16px;color:#000;">
    <div class="content">
        <h4> Subject: <small class="text-info">Test</small></h4>
    </div>
    <div class="content">
        {{$body ?? 'test'}}
    </div>
  </div>
</body>
</html>