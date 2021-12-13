<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
* { margin: 0; padding: 0; font-size: 100%; font-family: 'Avenir Next', "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; line-height: 1.15; }

img { max-width: 100%; margin: 0 auto; display: block; }

body, .body-wrap { width: 100% !important; height: 100%; background: #f8f8f8; font-size:16px;}

a { color: #71bc37; text-decoration: none; }

a:hover { text-decoration: underline; }

.text-center { text-align: center; }

.text-right { text-align: right; }

.text-left { text-align: left; }

.button { display: inline-block; color: white; background: #71bc37; border: solid #71bc37; border-width: 10px 20px 8px; font-weight: bold; border-radius: 4px; }

.button:hover { text-decoration: none; }

h1, h2, h3, h4, h5, h6 { margin-top: 1rem; }

h1 { font-size: 2rem; }

h2 { font-size: 1.75rem; }

h3 { font-size: 1.5rem; }

h4 { font-size: 1.25rem; }

h5 { font-size: 1rem; }

small { font-size: 0.9em; }

p, ul, ol { font-size: 16px; font-weight: normal; margin: 0; }

.container { display: block !important; clear: both !important; margin: 0 auto !important; max-width: 580px !important; }

.container table { width: 100% !important; border-collapse: collapse; }

.container .masthead { padding: 80px 0; background: #71bc37; color: white; }

.container .masthead h1 { margin: 0 auto !important; max-width: 90%; text-transform: uppercase; }

.container .content { background: white; padding: 0.5rem; }

.container .content.footer { background: none; }

.container .content.footer p { margin-bottom: 0; color: #888; text-align: center; font-size: 14px; }

.container .content.footer a { color: #888; text-decoration: none; font-weight: bold; }

.container .content.footer a:hover { text-decoration: underline; }

.text-primary { color: #007bff!important; }

.text-secondary { color: #6c757d!important; }

.text-success { color: #28a745!important; }

.text-danger { color: #dc3545!important; }

.text-warning { color: #ffc107!important; }

.text-info { color: #17a2b8!important; }

.text-light { color: #f8f9fa!important; }

.text-dark { color: #343a40!important; }

.text-white { color: #fff!important; }

.text-black-50 { color: rgba(0,0,0,.5)!important; }

.text-white-50 { color: rgba(255,255,255,.5)!important; }

    </style>
</head>
<body>
    <div class="container" style="font-size:16px; color:#000;">
        <div class="content">
            <h4>Subject: <small class="text-info">{{ $subject }}</small></h4>
        </div>
        <div class="content">
            @foreach ($fields as $name => $value)
                <p>
                    @if(!empty($name))
                        {{ $name }} :
                    @endif
                    @if (strpos($value, 'http') !== FALSE)
                        <a href="{{$value}}" class="text-primary">{{$value}}</a>
                    @else
                        <span class="text-primary">{{ $value }}</span>
                    @endif
                </p>
            @endforeach
        </div>
    </div>
</body>
</html>