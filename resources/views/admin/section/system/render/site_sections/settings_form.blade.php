<p class="color-gray fz14px mb2rem">Выбрать настройки, которые передадутся в выбранный раздел. В разделах клиентской части подключать: <code>$setting['назнание настройки']</code></p>
<div class="row row-cols-2 gx-5" id="sectionSettingsForm">
	@foreach($settings as $settingGroup => $settingsData)
		<div class="col">
			<p class="mb1rem"><span class="color-gray">Группа:</span> <strong class="fz16px mb5px">{{$settingGroup}}</strong></p>
			
			<div class="mb2rem">
				@foreach($settingsData as $settingItem)
					<div class="row row-cols-2 gx-5{{$loop->index > 0 ? ' mt3px' : ''}}">
						<div class="col-auto">
							<x-checkbox
								id="settingCheckbox{{$settingItem['key']}}"
								size="normal"
								name="{{$settingItem['key']}}"
								checked="{{in_array($settingItem['key'], $sectionSettings)}}"
							 />
						</div>
						<div class="col">
							<label for="settingCheckbox{{$settingItem['key']}}" class="pointer color-gray-600 color-black-hovered">{{$settingItem['key']}}</label>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	@endforeach
</div>