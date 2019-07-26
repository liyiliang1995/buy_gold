<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> {{ $header }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="renderer" content="webkit">
    @foreach($css as $c)
        <link rel="stylesheet" href="{{ asset("$c") }}?version=1.02">
    @endforeach
    <script src="https://cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
</head>
@yield('content')
@foreach($js as $j)
    <script src="{{ asset ("$j") }}?version=1.01"></script>
@endforeach
<script type="text/javascript">
    @if(!empty($script))
    $(function(){
        @foreach($script as $s)
        {!! $s !!}
        @endforeach
    })
    @endif
</script>
</html>