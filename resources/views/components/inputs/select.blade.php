@aware([
	'settings' 	=> null,
	'groupWrap'	=> null,
])

@props([
    'id' 			=> 'select'.rand(0,9999999),
	'value' 		=> null,
	'disabled'		=> false,
	'enabled'		=> true,
	'setting' 		=> false,
	'group' 		=> $groupWrap,
	'size'			=> $groupWrap,
	'label' 		=> null,
    'action'        => 'setSetting',
    'exclude'		=> [],
])

@php
	$group = $group ?? $size;
@endphp



{{-- 
choose="" - текст пункта [не выбрано]
empty="" - текст, когда нет записей
empty-has-value - добавить пункт [не выбрано]
choose-empty - разрешить выбирать пункт [не выбрано]
--}}


<div {{$attributes->class([
		'select',
		$group.'-select' => $group,
		($group ? $group.'-' : '').'select_noempty' => $hasActive || $value,
		($group ? $group.'-' : '').'select_disabled' => !$options || $disabled || !$enabled,
	])}}>
	@if($label)
		<label
			@class([
				'select__label',
				$group.'-select__label' => $group,
				'noselect'
			])
		 	for="{{$id}}"
		 >{{$label}}</label>	
	@endif
	
	
	<select
		@if($name)name="{{$name}}" @endif
		id="{{$id}}"
		@isset($group)inpgroup="{{$group}}" @endisset
		@if($disabled || !$enabled)disabled @endif
		@if($setting)oninput="$.{{$action}}(this, '{{$setting}}')" @endif
		@if(isset($actionFunc) && !$setting)oninput="$.{{$actionFunc}}(this{{isset($actionParams) ? ', '.$actionParams : null}})" @endif
		@if($tag) {!!$tag!!} @endif
		>
		@if ($options && $choose && ($emptyHasValue || (!$hasActive && $chooseEmpty && $value !== null && !$setSelected($value, $settings, $setting))))
			<option value=""{{!$hasActive ? 'selected' : ''}} {{$chooseEmpty ? '' : 'disabled'}}>{{$choose}}</option>
		@endif
		@forelse ($options as $item)
			@if(in_array($item['value'], $exclude))
				@continue;
			@endif
			<option
				value="{{$item['value'] ?? $item['title']}}"
				@selected($item['active'] ?? (($item['value'] ?? $item['title']) == $setSelected($value, $settings, $setting)))
				@if(isset($item['disabled']) && $item['disabled'])disabled @endisset
				>{{$item['title']}}</option>
		@empty
			<option value="" disabled selected>{{$empty}}</option>
		@endforelse
	</select>
	
	<div class="{{($group ? $group.'-' : '').'select__errorlabel'}} noselect" errorlabel></div>
</div>


{{-- @pushOnce('scripts')
    <script>
        console.log('admin', $('#roolFile').length);
    </script>
@endPushOnce --}}