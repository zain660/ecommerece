<meta charset="UTF-8">
<!-- Viewport-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
<!-- SEO Meta Tags--> 
<title>Chat - {{$product_details->product_name}}</title>
<!-- Favicon and Touch Icons-->
<link rel="apple-touch-icon" sizes="180x180" href="{{asset('chat_assets/assets/apple-touch-icon.png')}}">
<link rel="icon" type="image/png" sizes="32x32" href="{{asset('chat_assets/assets/favicon-32x32.png')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{asset('chat_assets/assets/favicon-16x16.png')}}">
<link rel="shortcut icon" href="{{asset('chat_assets/assets/favicon.ico')}}" />
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">

<link rel="stylesheet" href="{{asset('chat_assets/assets/webfonts/inter/inter.css')}}"> 
<link rel="stylesheet" href="{{asset('chat_assets/assets/css/app.min.css')}}">
<link rel="stylesheet" type="text/css"href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

@php
    $data = Modules\Appearance\Entities\ThemeColor::where('status',1)->first();
@endphp
<style>
    :root{
        background-color: '#{{$data->background_color}}';
    }
</style>