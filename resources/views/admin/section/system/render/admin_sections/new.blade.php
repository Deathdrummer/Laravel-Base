<x-data :data="$data">
	<x-table.tr class="h5rem" index="{{$index}}">
		<x-table.td>
			<x-input name="section" size="small" />
		</x-table.td>
		<x-table.td>
			<x-input name="title" size="small" />
		</x-table.td>
		<x-table.td>
			<x-input name="page_title" size="small" />
		</x-table.td>
		<x-table.td>
			<x-select
				name="parent_id"
				size="small"
				class="w100"
				options-type="assoc"
				:options="$data['parentItems']"
				/>
		</x-table.td>
		<x-table.td class="h-center">
			<x-checkbox
				name="visible"
				size="normal"
				/>
		</x-table.td>
		<x-table.td></x-table.td>
		<x-table.td>
			<x-input type="number" showrows name="sort" size="small" value="0" />
		</x-table.td>
		<x-table.td class="h-end pr9px">
			<x-buttons-group group="small" w="3rem" gx="5">
				<x-button
					variant="blue"
					action="adminSectionsSave"
					title="Сохранить"
					disabled
					save
					><i class="fa-solid fa-fw fa-floppy-disk"></i></x-button>
				<x-button
					variant="red"
					action="adminSectionsRemove"
					title="Удалить"
					remove
					><i class="fa-solid fa-fw fa-trash-can"></i></x-button>
			</x-buttons-group>
		</x-table.td>
	</x-table.tr>
</x-data>