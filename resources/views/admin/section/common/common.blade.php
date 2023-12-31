<section>
	<x-settings>
		<div class="row g-10">
			<div class="col-6">
				<x-card
					loading="{{__('ui.loading')}}"
					ready
					>
					
					
					<x-button variant="neutral" size="normal" action="getFilesAction" class="mb1rem">get files</x-button>
					
					
					
					<x-button variant="neutral" size="normal" id="uploadBtn" class="mb1rem">upload</x-button>
					
					
					
					
					<div
						id="dropFilesBlock"
						class="w20rem h20rem border-all border-gray d-block dhover"
						>
					</div>
					
					
					<div id="uploadedeFilesBlock" class="row row-cols-6 gx-5 gy-5 mt2rem"></div>
					
					
					
					
					{{-- <div class="w50rem h50rem border border-all border-blue border-round-10" style="position: relative;">
						
						
						<x-file id="singleFile" multiple>
							<x-button for="singleFile">click</x-button>
						</x-file>
						<x-drop
							for="singleFile"
							class="border-all border-gray d-block"
							multiple
							drop="tool"
							dragover="foo"
							dragleave="bar"
							style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
							>
							<p>sdfsdfs</p>
						</x-drop>
					</div> --}}
					
					
						
					
					{{-- 
					<label for="singleFile" id="singleFileDrop" class="w10rem h10rem border-all border-gray d-block" draggable="true"></label>
					
					<div id="singleFileBlock" class="row row-cols-6 gx-5 gy-5"></div> --}}
					
					
					
					
					
					{{-- <div class="ddrtabs">
						<div class="ddrtabs__nav">
							<ul class="ddrtabsnav" ddrtabsnav>
								<li class="ddrtabsnav__item ddrtabsnav__item_active" ddrtabsitem="testTab1">Название вкладки 1</li>
								<li class="ddrtabsnav__item" ddrtabsitem="testTab2">Название вкладки 2</li>
								<li class="ddrtabsnav__item" ddrtabsitem="testTab3">Название вкладки 3</li>
								<li class="ddrtabsnav__item" ddrtabsitem="testTab4">Название вкладки 4</li>
							</ul>
						</div>
						
						<div class="ddrtabs__content ddrtabscontent" ddrtabscontent>
							<div class="ddrtabscontent__item ddrtabscontent__item_visible" ddrtabscontentitem="testTab1">
								<x-input label="Название компании" group="large" setting="company_name" />
							</div>
							<div class="ddrtabscontent__item" ddrtabscontentitem="testTab2">
								Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus totam minus, explicabo, error adipisci nulla labore eaque molestiae id tempore?
							</div>
							<div class="ddrtabscontent__item" ddrtabscontentitem="testTab3">
								Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut mollitia quo consectetur fugiat eveniet officia voluptatibus. Tempora dolores aspernatur culpa cumque aliquam, ab eaque. Error assumenda nostrum possimus fugit autem officia blanditiis sint totam, quisquam.
							</div>
							<div class="ddrtabscontent__item" ddrtabscontentitem="testTab4">
								Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum excepturi eveniet enim aspernatur voluptates nam a qui alias atque ducimus quidem officiis, consequatur architecto, ea distinctio.
							</div>
						</div>
					</div> --}}
				</x-card>
			</div>
			
			{{-- <div class="col-6">
				<x-card
					loading="{{__('ui.loading')}}"
					ready
					>
					
					<x-textarea placeholder="Введите значение" group="large" label="Большой текст" setting="textarea" />
					<x-textarea placeholder="Введите значение" group="normal" label="Нормальны текст" />
					<x-textarea placeholder="Введите значение" group="small" label="Мамленький текст" />
				</x-card>
			</div>
			
			<div class="col-4">
				<x-card
					loading="{{__('ui.loading')}}"
					ready
					>
					<div class="mb15px"><x-input type="password" placeholder="Введите значение" group="large" label="Большой инпут" setting="common:large" /></div>
					<div class="mb15px"><x-input type="password" placeholder="Введите значение" group="normal" label="Нормальны инпут" setting="common:normal" /></div>
					<div class="mb15px"><x-input type="password" placeholder="Введите значение" group="small" label="Мамленький инпут" setting="common:small" /></div>
				</x-card>
			</div>
			
			<div class="col-4">
				<x-card
					loading="{{__('ui.loading')}}"
					ready
					>
					<div class="mb15px"><x-radio group="large" label="Радиокнапка большая" value="foo" setting="common:radio" /></div>
					<div class="mb15px"><x-radio group="large" label="Радиокнапка большая" value="bar" setting="common:radio" /></div>
					<div class="mb15px"><x-radio group="normal" label="Радиокнапка нормальная" setting="common:radio" /></div>
					<div class="mb15px"><x-radio group="small" label="Радиокнапка маленькая" setting="common:radio" /></div>
				</x-card>
			</div>
			
			<div class="col-4">
				<x-card
					loading="{{__('ui.loading')}}"
					ready
					>
					<div class="mb15px"><x-checkbox group="large" label="Чекбокс большой" setting="common:check" /></div>
					<div class="mb15px"><x-checkbox group="normal" label="Чекбокс нормальный" /></div>
					<div class="mb15px"><x-checkbox group="small" label="Чекбокс маленький" /></div>
				</x-card>
			</div>
			
			<div class="col-4">
				<x-card
					loading="{{__('ui.loading')}}"
					ready
					>
					<div class="mb15px"><x-select class="w100" group="large" label="Селект большой" /></div>
					<div class="mb15px"><x-select class="w100" group="normal" label="Селект нормальный" /></div>
					<div class="mb15px"><x-select class="w100" group="small" label="Селект маленький" /></div>
				</x-card>
			</div>
			
			<div class="col-8">
				<x-card
					loading="{{__('ui.loading')}}"
					ready
					title="Простой список"
					desc="Это простое описание данного списка для примера."
					>
					@cando('admin-dostup-k-razdelu-kakomu-to:admin')
					<x-simplelist
						setting="testlist"
						fieldset="Поле ввода:w20rem|input|name_title,Текстовое поле:w20rem|textarea|name_text,Выпадающий список:w20rem|select|name_type,Радио:w20rem|radio|name_radio,Чекбокс|checkbox|name_checkbox"
						options="name_type;foo:fooval,bar:barval|name_radio;foo:rool,bar:tool"
						group="small"
						/>
					
					@endcando
				</x-card>	
			</div>
			
			<div class="col-12">
				<x-card
					loading="{{__('ui.loading')}}"
					ready
					>
					<fieldset class="fieldset mb20px">
						<legend>Большие кнопки</legend>
						<x-buttons-group group="large" class="mb20px">
							<x-button variant="blue" action="openPopupWin">Кнопка большая</x-button>
							<x-button variant="gray"><i class="fa-solid fa-floppy-disk"></i> Кнопка большая</x-button>
							<x-button variant="neutral">Кнопка большая</x-button>
							<x-button variant="green">Кнопка большая</x-button>
							<x-button variant="red">Кнопка большая</x-button>
							<x-button variant="yellow">Кнопка большая</x-button>
							<x-button variant="purple">Кнопка большая</x-button>
							<x-button variant="light">Кнопка большая</x-button>
							<x-button variant="dark">Кнопка большая</x-button>
						</x-buttons-group>
					</fieldset>
					
					<fieldset class="fieldset mb20px">
						<legend>Нормальные кнопки</legend>
						<x-buttons-group group="normal">
							<x-button variant="blue">Кнопка нормальная</x-button>
							<x-button variant="gray">Кнопка нормальная</x-button>
							<x-button variant="neutral">Кнопка нормальная</x-button>
							<x-button variant="green">Кнопка нормальная</x-button>
							<x-button variant="red">Кнопка нормальная</x-button>
							<x-button variant="yellow">Кнопка нормальная</x-button>
							<x-button variant="purple">Кнопка нормальная</x-button>
							<x-button variant="light">Кнопка нормальная</x-button>
							<x-button variant="dark">Кнопка нормальная</x-button>
						</x-buttons-group>
					</fieldset>
					
					<fieldset class="fieldset mb20px">
						<legend>Маленькие кнопки</legend>
						<x-buttons-group group="small">
							<x-button variant="blue">Кнопка маленькая</x-button>
							<x-button variant="gray">Кнопка маленькая</x-button>
							<x-button variant="neutral">Кнопка маленькая</x-button>
							<x-button variant="green">Кнопка маленькая</x-button>
							<x-button variant="red">Кнопка маленькая</x-button>
							<x-button variant="yellow">Кнопка маленькая</x-button>
							<x-button variant="purple">Кнопка маленькая</x-button>
							<x-button variant="light">Кнопка маленькая</x-button>
							<x-button variant="dark">Кнопка маленькая</x-button>
						</x-buttons-group>
					</fieldset>
					
					<fieldset class="fieldset mb20px">
						<legend>Очень маленькие кнопки</legend>
						<x-buttons-group group="verysmall">
							<x-button variant="blue">Кнопка очень маленькая</x-button>
							<x-button variant="gray">Кнопка очень маленькая</x-button>
							<x-button variant="neutral">Кнопка очень маленькая</x-button>
							<x-button variant="green">Кнопка очень маленькая</x-button>
							<x-button variant="red">Кнопка очень маленькая</x-button>
							<x-button variant="yellow">Кнопка очень маленькая</x-button>
							<x-button variant="purple">Кнопка очень маленькая</x-button>
							<x-button variant="light">Кнопка очень маленькая</x-button>
							<x-button variant="dark">Кнопка очень маленькая</x-button>
						</x-buttons-group>
					</fieldset>
						
					<fieldset class="fieldset">
						<legend>Запрещенные кнопки</legend>
						
						<x-button variant="blue" disabled group="large">Кнопка большая</x-button>
						<x-button variant="blue" disabled group="normal">Кнопка нормальная</x-button>
						<x-button variant="blue" disabled group="small">Кнопка маленькая</x-button>
						<x-button variant="blue" disabled group="verysmall">Кнопка очень маленькая</x-button>
					
					</fieldset>
						
						
						
						
					
				</x-card>
			</div> --}}
			
		</div>
		
	</x-settings>
</section>











<script type="module">
	
	
	/*$('#uploadBtn').ddrFiles('choose', {
		multiple: true,
		init({count}) {
			console.log('before', count);
		},
		preload({key, iter}) {
			console.log('preload', key, iter);
		},
		callback(fileData, complete, cbIters) {
			console.log('callback', fileData, complete, cbIters);
		},
		done(allFiles) {
			console.log('done', allFiles);
		},
		fail(file) {
			console.log('fail', file);
		},
	});
	
	
	
	$('#dropFilesBlock').ddrFiles('drop', {
		dragover(selector) {
			$(selector).addClass('dhover-hovered');
		},
		dragleave(selector) {
			$(selector).removeClass('dhover-hovered');
		},
		init({count}) {
			console.log('before', count);
		},
		preload({key, iter}) {
			console.log('preload', key, iter);
		},
		callback(fileData, complete, cbIters) {
			console.log('callback', fileData, complete, cbIters);
		},
		done(allFiles) {
			console.log('done', allFiles);
		}
	});*/
	
	
	const {getFiles, removeFile} = $.ddrFiles({
		chooseSelector: '#uploadBtn',
		dropSelector: '#dropFilesBlock',
		multiple: true,
		dragover(selector) {
			$(selector).addClass('dhover-hovered');
		},
		dragleave(selector) {
			$(selector).removeClass('dhover-hovered');
		},
		init({count}) {
			$('#uploadedeFilesBlock').empty();
			for (let i = 0; i < count; i++) {
				let rool = $('<div class="col" filecontainer><div class="w100 h10rem border border-gray border-all border-round-5"></div></div>');
				
				$(rool).find('[filecontainer]').children().ddrWait({
					iconHeight: '30px',
					bgColor: '#fff3',
				});
				
				$('#uploadedeFilesBlock').append(rool);
			}
		},
		preload({key, iter, error}) {
			$('#uploadedeFilesBlock').find('[filecontainer]').eq(iter).children().setAttrib('file-id', key);
		},
		async callback({file, name, ext, key, size, type, isImage, preview, error}, {done, index}) {
			if (error) {
				console.log(error);
				return false;
			}
			
			let imgSrc;
			if (isImage) {
				imgSrc = await preview({width: 100});
			} else {
				imgSrc = await loadImage(`filetypes/${ext}.png`);
			}
			
			$('#uploadedeFilesBlock').find(`[file-id="${key}"]`).html(`<img class="w100 h100" style="object-fit: contain;" onerror="$.errorLoadingImage(this)" src="${imgSrc}" /><p class="fz12px lh100 text-center">${name}</p>`);
			
			if (done) {
				$.notify('Готово!');
			} 
		},
		done({files}) {
			//console.log('done', files);
		},
	});
	
	
	
	$.getFilesAction = () => {
		const rool = getFiles();
		console.log(rool);
	}
	
	
	$('#uploadedeFilesBlock').on(tapEvent, '[file-id]', function() {
		let key = $(this).attr('file-id');
		removeFile(key);
		$(this).closest('.col').remove();
		console.log(getFiles());
	});
	
	
	
	
	
	
	
	
	
	$.openPopupWin = () => {
		ddrPopup({
			
			title: 'Тестовый заголовок',
			width: 400, // ширина окна
			html: '<p>Контентная часть</p>', // контент
			buttons: ['ui.close', {action: 'tesTest', title: 'Просто кнопка'}],
			buttonsAlign: 'center', // выравнивание вправо
			//disabledButtons, // при старте все кнопки кроме закрытия будут disabled
			//closeByBackdrop, // Закрывать окно только по кнопкам [ddrpopupclose]
			//changeWidthAnimationDuration, // ms
			//buttonsGroup, // группа для кнопок
			//winClass, // добавить класс к модальному окну
			//centerMode, // контент по центру
			//topClose // верхняя кнопка закрыть
		}).then(({state, wait, setTitle, setButtons, loadData, setHtml, setLHtml, dialog, close, onScroll, disableButtons, enableButtons, setWidth}) => { //isClosed
						
		});
	}
	
	
	//$('button').ddrInputs('disable');
	
	
	/*$('#testRool').ddrInputs('error', 'error');
	$('#testSelect').ddrInputs('error', 'error');
	$('#testCheckbox').ddrInputs('error', 'error');
	
	
	$('#openPopup').on(tapEvent, function() {
		ddrPopup({
			title: 'auth.greetengs',
			lhtml: 'auth.agreement'
		}).then(({wait}) => {
			//wait();
		});
	});*/
</script>

