@aware([
	'groupWrap'	=> null,
])

@props([
	'id' 	=> 'localebar'.rand(0,9999999),
	'guard'	=> 'site',
	'group'	=> $groupWrap,
	'size'	=> $groupWrap,
])

@php
	$group = $group ?? $size;
@endphp

<div id="{{$id}}" {{$attributes->class(['localebar', $group.'-localebar' => $group, 'noselect'])}}>
	<div @class([
			'localebar__handler',
			$group.'-localebar__handler' => $group
		])
		touch="{{($group ? $group.'-' : '')}}localebar_opened"
		title="{{$locales[$currentLocale]['title']}}"
		>
		
		<div><img src="{{Vite::asset('resources/images/flags/'.$currentLocale.'.svg')}}" alt="{{$locales[$currentLocale]['abbr']}}"></div>
		<span class="localebar__label">{{$locales[$currentLocale]['abbr']}}</span>
		<i class="fa-solid fa-chevron-down"></i>
	</div>
	
	<div @class([
			'localebar__dropdown',
			$group.'-localebar__dropdown' => $group
		])>
		<ul @class([
				'localebar__list',
				$group.'-localebar__list' => $group
			])>
			@foreach($locales as ['locale' => $locale, 'abbr' => $abbr, 'title' => $title])
				<li
					value="{{$locale}}"
					@class(['active' => $currentLocale == $locale])
					chooselocale="{{$locale}}"
					title="{{$title}}"
					>
					<div><img src="{{Vite::asset('resources/images/flags/'.$locale.'.svg')}}" alt="{{$abbr}}"></div>
				 	<span>{{$abbr}}</span>
				 </li>
			@endforeach
		</ul>
	</div>
</div>


<script type="module">
	const selector = '#{{$id}}',
		guard = '{{$guard}}',
		readyCls = '{{($group ? $group.'-' : '')}}localebar_ready',
		openedCls = '{{($group ? $group.'-' : '')}}localebar_opened';
	
	
	setTimeout(() => {
		$(selector).addClass(readyCls);
	}, 500);
	
	
	$(selector).find('[chooselocale]').on(tapEvent, function() {
		if ($(this).hasClass('active')) return false;
		let locale = $(this).attr('chooselocale');
		
		$(selector).removeClass(openedCls);
		$(selector).find('[touch]').attr('aria-expanded', false);
		
		
		let route = guard == 'site' ? '/set_lang' : ((guard == 'admin' ? '/admin/set_lang' : null));
		
		axios.post(route, {locale}, {
			responseType: 'json'
		}).then(function ({data, status, statusText, headers, config}) {
			if (data?.status) {
				$.notify(data?.message, 'error');
			} else if (status == 200) {
				pageReload();
			}
		}).catch(err => {
			console.log(err);
		});
	});
	
</script>