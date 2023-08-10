<x-table.tr class="h5rem">
	<x-table.td>
		<x-input name="section" size="small" value="{{$section}}" />
	</x-table.td>
	<x-table.td>
		<x-input name="title" size="small" value="{{$title}}" />
	</x-table.td>
	<x-table.td>
		<x-input name="page_title" size="small" value="{{$page_title}}" />
	</x-table.td>
	<x-table.td>
		<x-select
			name="parent_id"
			size="small"
			class="w100"
			:options="$data['parentItems']"
			value="{{$parent_id}}"
			options-type="assoc"
			:exclude="[$id]"
			/>
	</x-table.td>
	<x-table.td class="h-center">
		<x-checkbox
			name="visible"
			size="normal"
			checked="{{!!$visible}}"
			/>
	</x-table.td>
	<x-table.td class="h-center" createdfiles>
		@if(isset($created_files) && $created_files)
			<i class="fa-solid fa-fw fa-check color-green fz18px"></i>
		@endif
	</x-table.td>
	<x-table.td>
		<x-input type="number" showrows name="sort" size="small" value="{{$sort}}" />
	</x-table.td>
	<x-table.td class="h-center">
		<x-buttons-group group="small" w="3rem" gx="5">
			<x-button
				variant="purple"
				action="adminSectionsCreateFiles:{{$id ?? null}},{{$section}},{{$title}}"
				tag="adminsectionscreatefilesbtn"
				title="Создать структуру файлов"
				disabled="{{isset($created_files) && $created_files}}"
				>
				<i class="fa-solid fa-fw fa-folder-tree"></i>
			</x-button>
			<x-button
				variant="blue"
				action="adminSectionsUpdate:{{$id ?? null}}"
				title="Сохранить"
				disabled
				update
				>
				<i class="fa-solid fa-fw fa-floppy-disk"></i>
			</x-button>
			<x-button
				variant="red"
				action="adminSectionsRemove:{{$id ?? null}}"
				title="Удалить"
				remove
				>
				<i class="fa-solid fa-fw fa-trash-can"></i>
			</x-button>
		</x-buttons-group>
	</x-table.td>
</x-table.tr>