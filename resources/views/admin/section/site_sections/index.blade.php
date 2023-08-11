<section>
	<x-card
		loading="{{__('ui.loading')}}"
		ready
		class="p0"
		id="sectionsTabSCard"
		>
		<div class="ddrtabs">
			<div class="ddrtabs__nav">
				<ul class="ddrtabsnav" ddrtabsnav>
					<li
						class="ddrtabsnav__item ddrtabsnav__item_active"
						ddrtabsitem="sectionsTabSectionsAdmin"
						onclick="$.loadSiteSections(this, 'admin', this.classList.contains('ddrtabsnav__item_active'))"
						>Разделы админ. панели</li>
					<li
						class="ddrtabsnav__item"
						ddrtabsitem="sectionsTabSectionsSite"
						onclick="$.loadSiteSections(this, 'site', this.classList.contains('ddrtabsnav__item_active'))"
						>Разделы клиентской части</li>
				</ul>
			</div>
			
			{{--  --}}
			<div class="ddrtabs__content ddrtabscontent" ddrtabscontent>
				<div class="ddrtabscontent__item ddrtabscontent__item_visible" ddrtabscontentitem="sectionsTabSectionsAdmin">
					<x-table class="w100" noborder id="adminSectionsTable" scrolled="calc(100vh - 216px)">
						<x-table.head>
							<x-table.tr class="h5rem">
								<x-table.td class="w20rem" noborder><strong>Имя файла (URL)</strong></x-table.td>
								<x-table.td class="w25rem" noborder><strong>Название раздела (в меню)</strong></x-table.td>
								<x-table.td class="w-auto" noborder><strong>Заголовок на странице</strong></x-table.td>
								<x-table.td class="w25rem" noborder><strong>Родительский раздел</strong></x-table.td>
								<x-table.td class="w7rem" noborder><strong>Активен</strong></x-table.td>
								<x-table.td class="w7rem" noborder><strong>Файлы</strong></x-table.td>
								<x-table.td class="w6rem" noborder><strong>Сорт.</strong></x-table.td>
								<x-table.td class="w12rem" noborder><strong>Опции</strong></x-table.td>
							</x-table.tr>
						</x-table.head>
						<x-table.body id="adminSectionsList" class="minh-5rem" emptytext="Нет разделов"></x-table.body>
						<x-table.foot>
							<x-table.tr class="justify-content-end">
								<x-button action="adminSectionsAddAction" class="mt5px" size="small" variant="blue">Добавить раздел</x-button>
							</x-table.tr>
						</x-table.foot>
					</x-table>
				</div>
				
				<div class="ddrtabscontent__item" ddrtabscontentitem="sectionsTabSectionsSite">
					<x-table class="w100" noborder id="siteSectionsTable" scrolled="calc(100vh - 216px)">
						<x-table.head>
							<x-table.tr class="h5rem">
								<x-table.td class="w20rem" noborder><strong>Имя файла (URL)</strong></x-table.td>
								<x-table.td class="w25rem" noborder><strong>Название раздела (в меню)</strong></x-table.td>
								<x-table.td class="w-auto" noborder><strong>Заголовок на странице</strong></x-table.td>
								<x-table.td class="w25rem" noborder><strong>Родительский раздел</strong></x-table.td>
								<x-table.td class="w7rem" noborder><strong>Активен</strong></x-table.td>
								<x-table.td class="w7rem" noborder><strong>Файлы</strong></x-table.td>
								<x-table.td class="w6rem" noborder><strong>Сорт.</strong></x-table.td>
								<x-table.td class="w12rem" noborder><strong>Опции</strong></x-table.td>
							</x-table.tr>
						</x-table.head>
						<x-table.body id="siteSectionsList" class="minh-5rem" emptytext="Нет разделов"></x-table.body>
						<x-table.foot>
							<x-table.tr class="justify-content-end">
								<x-button action="siteSectionsAddAction" class="mt5px" size="small" variant="blue">Добавить раздел</x-button>
							</x-table.tr>
						</x-table.foot>
					</x-table>
				</div>
				
			</div>
			{{--  --}}
			
		</div>
	</x-card>
</section>








<script type="module">
	const {adminSectionsCrud, siteSectionsCrud} = await loadSectionScripts({guard: 'admin', section: 'sections'});
	
	
	adminSectionsCrud();
	
	
	$.loadSiteSections = (tab, type, isActive) => {
		if (isActive) return;
		
		$('#sectionsTabSCard').find('[ddrtablebody]').empty();
		
		if (type == 'admin') adminSectionsCrud();
		if (type == 'site') siteSectionsCrud();
	}
	
	
</script>