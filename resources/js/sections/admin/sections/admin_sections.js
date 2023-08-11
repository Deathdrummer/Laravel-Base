const viewsPath = 'admin.section.site_sections.render.admin_sections';
	

export async function adminSectionsCrud() {
	
	$.ddrCRUD({
		container: '#adminSectionsList',
		waitParams: {
			iconHeight: '40px',
			iconColor: 'hue-rotate(106deg)',
		},
		itemToIndex: '[ddrtabletr]',
		route: 'system/admin_sections',
		params: {
			//list: {archive: 0/*department_id: deptId*/},
			//create: {guard: 'admin'},
			//edit: {guard: 'admin'}
			//store: {department_id: deptId},
		},
		viewsPath,
	}).then(({error, list, changeInputs, create, store, storeWithShow, edit, update, destroy, query, getParams, abort, remove}) => {
		
		if (error) {
			console.log(error.message);
			//$.notify(error.message, 'error');
			$('#adminSectionsTable').blockTable('error', error.message);
			return false;
		}
		
		//$('#adminSectionsTable').blockTable('buildTable');
		//wait(false);
		//enableButtons(true);
		changeInputs({'[save], [update]': 'enable'});
		
		
		$.timesheetPeriodsWinBuild = (btn, periodId) => {
			$('#lastTimesheetPeriodsBlock').find('li').removeClass('active');
			$('#newTimesheetEventBtn, #importTimesheetEventsBtn, #exportOrdersBtn').setAttrib('hidden');
			choosedPeriod.value = periodId;
			
			if (_.isFunction(timesheetCrud)) {
				//timesheetCrud(periodId, listType);
				let buildOrdersTable = null;
				timesheetCrud(periodId, listType, buildOrdersTable, (list) => {
					timesheetCrudList.value = list;
				});
			}
			
			$('#lastTimesheetPeriodsBlock').find(`li[timesheetperiod="${periodId}"]`).addClass('active');
			close();
		}
		
		
		
		
		
		
		$.adminSectionsAddAction = (btn) => {
			let adminSectionsAddBtnWait = $(btn).ddrWait({
				iconHeight: '20px',
				bgColor: '#ffffff91'
			});
			
			create((data, container, {error}) => {
				adminSectionsAddBtnWait.destroy();
				if (data) $(container).append(data);
				if (error) $.notify(error.message, 'error');
				$('#adminSectionsTable').blockTable('scroll');
				//$('#adminSectionsTable').blockTable('buildTable');
			});
		}
		
		
		$.adminSectionsSave = (btn) => {
			let row = $(btn).closest('[ddrtabletr]');
			
			let adminSectionSaveWait = $(row).ddrWait({
				iconHeight: '26px',
				bgColor: '#ffffffd6'
			});
			
			storeWithShow(row, (data, container, {error, headers, payload}) => {
				if (error) {
					adminSectionSaveWait.destroy();
					$(row).ddrInputs('state', 'clear');
					$.notify(error.message, 'error');
				} 
				
				if (error.errors) {
					$.each(error.errors, function(field, errors) {
						$(row).find('[name="'+field+'"]').ddrInputs('error', errors[0]);
					});
				}
				
				if (data) {
					$(row).replaceWith(data);
					$.notify('Запись успешно сохранена!');
					//$('#adminSectionsTable').blockTable('buildTable');
					
					const {id, title} = payload;
					$('#adminSectionsTable').find('[name="parent_id"]').each((k, item) => {
						$(item).children().last().after(`<option value="${id}">${title}</option>`);
					});
				}
			});
		}
		
		
		
		
		$.adminSectionsUpdate = (btn, id) => {
			let row = $(btn).closest('[ddrtabletr]');
			
			const adminSectionUpdateBtnWait = $(btn).ddrWait({
				iconHeight: '15px',
				bgColor: '#ffffffd6'
			});
			
			const disableFields = $(row).ddrInputs('disable', ['inputs', '[remove]', '[adminsectionscreatefilesbtn]:not([disabled])']);
			$(row).ddrInputs('disable', ['[update]']);
			
			update(id, row, (stat, container, {error}) => {
				if (stat) {
					$(btn).ddrInputs('disable');
					$(row).find('input, select, textarea').ddrInputs('state', 'clear');
					$.notify('Запись успешно обновлена!');
				}
				
				if (error) $.notify(error.message, 'error');
				
				if (error.errors) {
					console.log(error.errors);
					$.each(error.errors, function(field, errors) {
						$(row).find('[name="'+field+'"]').ddrInputs('error', errors[0]);
					});
				}
				
				$(row).ddrInputs('enable', disableFields);
				adminSectionUpdateBtnWait.destroy(false);
			});
		}
		
		
		
		
		$.adminSectionsRemove = async (btn, id = null) => {
			const row = $(btn).closest('[ddrtabletr]');
			
			if (!id) {
				remove(row);
			} else {
				
				const {
					wait,
					close,
				} = await ddrPopup({
					title: 'Удалить Раздел',
					html: '<p class="color-red fz16px">Вы действительно хотите удалить раздел?</p>', // контент
					buttons: ['ui.cancel', {title: 'ui.delete', variant: 'red', action: 'adminSectionsRemoveAction'}],
					centerMode: true, // контент по центру
				});
				
				
				
				$.adminSectionsRemoveAction = (btn) => {
					wait();
					let removeAdminSectionWait = $(row).ddrWait({
						iconHeight: '15px',
						bgColor: '#ffffff91'
					});
					
					destroy(id, function(stat) {
						if (stat) {
							remove(row);
							$.notify('Раздел успешно удален!');
							
							close();
						} else {
							$.notify('Ошибка удаления периода!', 'error');
						} 
						wait(false);
						removeAdminSectionWait.destroy();
						
						$('#adminSectionsTable').find('[name="parent_id"]').each((k, item) => {
							$(item).children(`[value="${id}"]`).remove();
						});
					});
					
				}
				
			}
		}
		
		
		
		
		
		$.adminSectionsCreateFiles = async (btn, id, section, title) => {
			const row = $(btn).closest('[ddrtabletr]');
			
			const {
				wait,
				close,
			} = await ddrPopup({
				title: 'Cтруктура файлов раздела',
				//width, // ширина окна
				html: `<p class="fz16px color-green">Создать структуру файлов для раздела ${title}?</p>`, // контент
				buttons: ['ui.cancel', {title: 'ui.create', variant: 'purple', action: 'adminSectionsCreateFilesActions'}],
				centerMode: true,
			});
			
			
			$.adminSectionsCreateFilesActions = async (__) => {
				wait();
				
				const {data, error, status, headers} = await ddrQuery.post('system/admin_sections/create_files', {id, section});
				
				if (error) {
					$.notify(error.message, 'error');
					wait(false);
					return false;
				}
				
				if (data['isset']) {
					$.notify('Структура файлов уже создана!', 'info');
					close();
					return false;
				}
				
				if (data) {
					$(row).find('[createdfiles]').html('<i class="fa-solid fa-fw fa-check color-green fz18px"></i>');
					$(btn).ddrInputs('disable');
					$.notify('Структура успешно создана!');
					close();
				}
			}
			
			
			
		}
	
	});
	
	
}
