<x-table.tr class="minh5rem h{{(count(setting('locales_list')) * 3)}}rem-3px">
	<x-table.td class="v-start">
		<x-input name="section" size="small" value="{{$section}}" />
	</x-table.td>
	<x-table.td class="v-start">
		<div class="ddrlist">
			@foreach(setting('locales_list', 'locale') as $locale)
				<div class="ddrlist__item mt3px">
					<div class="row align-items-center g-5">
						<div class="col-auto w3rem text-end">
							<span class="color-gray">{{$locale}}:</span>
						</div>
						<div class="col">
							<x-input class="w100" name="title[{{$locale}}]" size="small" value="{{$title[$locale] ?? '---'}}" />
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</x-table.td>
	<x-table.td class="v-start">
		<div class="ddrlist">
			@foreach(setting('locales_list', 'locale') as $locale)
				<div class="ddrlist__item mt3px">
					<div class="row align-items-center g-5">
						<div class="col-auto w3rem text-end">
							<span class="color-gray">{{$locale}}:</span>
						</div>
						<div class="col">
							<x-input class="w100" name="page_title[{{$locale}}]" size="small" value="{{$page_title[$locale] ?? '---'}}" />
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</x-table.td>
	<x-table.td class="v-start">
		<p class="mt5px color-gray-600 fz14px">{{$data['parentItems'][$parent_id]}}</p>
		{{-- <x-select
			name="parent_id"
			size="small"
			class="w100"
			:options="$data['parentItems']"
			value="{{$parent_id}}"
			options-type="assoc"
			:exclude="[$id]"
			/> --}}
	</x-table.td>
	<x-table.td class="h-center v-start">
		<x-checkbox
			class="mt5px"
			name="visible"
			size="normal"
			checked="{{!!$visible}}"
			/>
	</x-table.td>
	<x-table.td class="h-center v-start" createdfiles>
		@if(isset($created_files) && $created_files)
			<i class="fa-solid fa-fw fa-check color-green fz18px mt3px"></i>
		@endif
	</x-table.td>
	<x-table.td class="v-start">
		<x-input type="number" showrows name="sort" size="small" value="{{$sort}}" />
	</x-table.td>
	<x-table.td class="h-center v-start">
		<x-buttons-group group="small" w="3rem" gx="5">
			<x-button
				variant="purple"
				action="siteSectionsCreateFiles:{{$id ?? null}},{{$section}},{{$title[config('app.locale')] ?? null}}"
				tag="sitesectionscreatefilesbtn"
				title="Создать структуру файлов"
				disabled="{{isset($created_files) && $created_files}}"
				>
				<i class="fa-solid fa-fw fa-folder-tree"></i>
			</x-button>
			<x-button
				variant="yellow"
				action="siteSectionsSetSettings:{{$id ?? null}},{{$section}},{{$title[config('app.locale')] ?? null}}"
				title="Настройки раздела"
				>
				<i class="fa-solid fa-fw fa-sliders"></i>
			</x-button>
			<x-button
				variant="blue"
				action="siteSectionsUpdate:{{$id ?? null}}"
				title="Сохранить"
				disabled
				update
				>
				<i class="fa-solid fa-fw fa-floppy-disk"></i>
			</x-button>
			<x-button
				variant="red"
				action="siteSectionsRemove:{{$id ?? null}}"
				title="Удалить"
				remove
				>
				<i class="fa-solid fa-fw fa-trash-can"></i>
			</x-button>
		</x-buttons-group>
	</x-table.td>
</x-table.tr>