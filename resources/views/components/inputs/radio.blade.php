@aware([
	'settings' 	=> null,
	'groupWrap'	=> null,
	'nameGroup'	=> null,
])

@props([
	'checked' 		=> false,
	'id' 			=> 'radio'.rand(0,9999999),
	'setting'		=> false,
	'name'			=> $nameGroup,
	'disabled'		=> false,
	'group' 		=> $groupWrap,
	'size'			=> $groupWrap,
	'label' 		=> null,
	'fieldset' 		=> null,
    'action' 		=> 'setSetting',
])

@php
	$group = $group ?? $size;
@endphp


<div {{$attributes->class(['radio', $group.'-radio' => $group, ($group ? $group.'-' : '').'radio_checked' => ($checked ?: $isChecked($settings, $setting))])}}>
	<input
		type="radio"
		@if($name || $setting)name="{{$name ?: $setting}}" @endif
		@if($value)value="{{$value}}" @endif
		@if($setting)oninput="$.{{$action}}(this, '{{$setting}}')" @endif
		@if(isset($actionFunc) && !$setting)oninput="$.{{$actionFunc}}(this{{isset($actionParams) ? ', '.$actionParams : null}})" @endif
		id="{{$id}}"
		fieldset="{{$setFieldset($fieldset, $name ?: $setting)}}"
		@checked($checked ?: $isChecked($settings, $setting)) 
		@isset($group) inpgroup="{{$group}}" @endisset
		@if($tag) {!!$tag!!} @endif
		>
		
	<label class="noselect" for="{{$id}}"></label>
	
	<label
		for="{{$id}}"
		@class([
			'radio__label',
			$group.'-radio__label' => $group,
			'noselect'
		])
		>{!!$label!!}</label>	
	
	<div class="{{($group ? $group.'-' : '').'radio__errorlabel'}}" errorlabel></div>
</div>