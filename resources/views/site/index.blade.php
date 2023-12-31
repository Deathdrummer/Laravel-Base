<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="UTF-8" />
		<meta name="author" content="Дмитрий Сайтотворец" />
		<meta name="copyright" content="ShopDevelop &copy; Web разработка" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="format-detection" content="telephone=no"> {{-- отключение автоопределения номеров для Safari (iPhone / IPod / IPad) и Android браузера --}}
		<meta http-equiv="x-rim-auto-match" content="none"> {{-- отключение автоопределения номеров для BlackBerry --}}
		<meta name="csrf-token" content="{{csrf_token()}}"> {{-- CSRF Token --}}
		
		{{-- не кэшировать, если это не продакшн версия --}}
		@unless(config('app.env') == 'production')
			<meta http-equiv="cache-control" content="no-cache">
			<meta http-equiv="expires" content="1">
		@endunless
	
		<link rel="shortcut icon" href="{{Vite::asset('resources/images/favicon.png')}}" />
		 
		@vite(['resources/sass/site.sass', 'resources/sass/common/app.sass', 'resources/js/site.js'])
		
		<title>{{$company_name ?? 'BPM systems'}}</title>
	</head>
    
	<body>
		@auth('site')
			@include('site.layout.site')
		@else
			@include('site.layout.auth')
		@endauth
		
		@stack('auth')
		@stack('scripts')
    </body>
</html>


<script type="module">
	let notifyAuth =  '{{$siteLogin ?? false}}' || null,
		notifyReg = '{{$siteRegister ?? false}}' || null,
		notifyResetPswd = '{{$siteResetPassword ?? false}}' || null,
		notifyEmailVerified = '{{$siteEmailVerified ?? false}}' || null;
	
	
	if (notifyAuth) {
		$.notify(notifyAuth);
	}
	
	
	if (notifyReg) {
		$.notify(notifyReg);
	}
	
	if (notifyResetPswd) {
		$.notify(notifyResetPswd);
	}
	
	if (notifyEmailVerified) {
		$.notify(notifyEmailVerified);
	}
	
	
</script>