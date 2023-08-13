<?php namespace App\Http\Controllers\System\Crud;

use App\Http\Controllers\Controller;
use App\Models\System\AdminSection;
use App\Traits\HasCrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class AdminSections extends Controller {
	use HasCrudController;
	
	
	/**
     * Глобальные данные
     * 	добавляются глобальные данные, которые будут доступны во всех записях списка.
     * 	В списке передается через компонент <x-data>
     * 	В новую запись передается напрямую, без компанента <x-data>
     * 	Данная переменная заполняется автоматически в трейте HasCrudController
     * 	Для добавления данных достаточно просто присвоить их переменной $this->data['название'] = значение (можно отдельно написать метод)
     * 	Для добавления данных из настроек вызвать метод из HasCrudController: 
     * 	$this->addSettingToGlobalData('ключ в настройках[:переименовать ключ]', 'значение в качестве ключа', ['значение в качестве значения'], 'поле для фильтрации[:значение]');
     *
     * @var array
     */
	protected $data = [];
	
	
	
	public function __construct() {
		
		/* 
		$this->middleware('throttle:10,1')->only([
			'store_show',
			'store',
			'update',
			'destroy',
		]);
		
		$this->middleware('lang')->only([
			'index',
			'create',
			'show',
			'store_show',
			'edit',
		]); */
		
	}
	
	
	
    /**
     * Вывод всех записей
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
		$validData = $request->validate([
			'views' => 'required|string',
		]);
		$viewPath = $validData['views'];
		if (!$viewPath) return response()->json(['no_view' => true]);
        
		$list = AdminSection::whereNot('section', 'site_sections')->orderBy('_sort', 'ASC')->get();
		
		$lang = App::currentLocale();
		$listToSelect = $list->where('parent_id', 0)->pluck("title.{$lang}", 'id');
		
		$listToSelect->prepend('-', 0);
		$this->data['parentItems'] = $listToSelect->toArray();
		
		$itemView = $viewPath.'.item';
		
		return $this->viewWithLastSortIndex(AdminSection::class, $viewPath.'.list', compact('list', 'itemView'), '_sort');
    }
	
	
	
    /**
     * Показ формы создания
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
		$viewPath = $request->input('views');
		$newItemIndex = $request->input('newItemIndex');
		if (!$viewPath) return response()->json(['no_view' => true]);
		
		$lang = App::currentLocale();
		$listToSelect = AdminSection::where('parent_id', 0)
			->orderBy('_sort', 'ASC')
			->get()
			->pluck("title.{$lang}", 'id');
		
		$listToSelect->prepend('Нет', 0);
		$this->data['parentItems'] = $listToSelect->toArray();
		
        return $this->view($viewPath.'.new', ['index' => $newItemIndex]);
    }
	
	
	
	

    /**
     * Создание ресурса
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
		$item = $this->_storeRequest($request);
		return response()->json($item);
    }
	
	
	
	/**
     * Создание ресурса и показ записи
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_show(Request $request) {
		$viewPath = $request->input('views');
		
		$item = $this->_storeRequest($request);
		
		if (!$viewPath) return response()->json(['no_view' => true]);
		
		return $this->view($viewPath.'.item', $item);
    }
	
	
	
	private function _storeRequest($request = null) {
		if (!$request) return false;
		
		$validFields = $request->validate([
			'section'		=> 'required|string|unique:admin_sections,section',
			'title'			=> 'required|array',
			'page_title'	=> 'required|array',
			'parent_id'		=> 'nullable|numeric',
			'visible'		=> 'required|boolean',
			'sort'			=> 'required|regex:/[0-9]+/',
			'_sort'			=> 'required|regex:/[0-9]+/',
		]);
		
		if (!$res = AdminSection::create($validFields)) return null;
		
		$lang = App::currentLocale();
		$listToSelect = AdminSection::where('parent_id', 0)
			->orderBy('_sort', 'ASC')
			->get()
			->pluck("title.{$lang}", 'id');
		
		$listToSelect->prepend('Нет', 0);
		$this->data['parentItems'] = $listToSelect->toArray();
		
		return $res;
	}
	
	
	

    /**
     * Показ определенной записи
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $viewPath = $request->input('views');
		$data = $request->except(['views']);
		if (!$viewPath) return response()->json(['no_view' => true]);
		return $this->view($viewPath.'.item', $data);
    }
	
	
	
	

    /**
     * Показ формы редактирования
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        logger('edit');
    }
	
	
	
	

    /**
     * Обновление ресурса
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
		$validFields = $request->validate([
			'section'	=> [
				'required',
        		'string',
				Rule::unique('admin_sections')->ignore(AdminSection::where('id', $id)->first()),
			],
			'title'			=> 'required|array',
			'page_title'	=> 'required|array',
			'parent_id'		=> 'nullable|numeric',
			'visible'		=> 'required|boolean',
			'sort'			=> 'required|regex:/[0-9]+/',
			//'_sort'			=> 'required|regex:/[0-9]+/'
		]);
		
		
		$adminSection = AdminSection::find($id);
		$adminSection->fill($validFields);
		$res = $adminSection->save();
		return response()->json($res);
    }
	
	
	
	
	
    /**
     * Удаление записи
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(?int $id = null) {
		$section = AdminSection::find($id);
		$sectionName = $section->section;
		$stat = $section->destroy($id);
		if ($stat) Permission::where('name', "section-{$sectionName}:admin")->delete();
		return response()->json($stat);
    }
	
	
	
	
	
	
	
	
	
	
	//------------------------------------------------------------------------------------------------------
	
	/**
	* 
	* @param 
	* @return 
	*/
	public function create_files(Request $request) {
		[
			'id' 		=> $id,
			'section' 	=> $section
			
		] = $request->validate([
			'id'	=> 'required|numeric',
			'section'	=> 'required|string',
		]);
		
		$adminSection = AdminSection::find($id);
		
		if ($adminSection->created_files) return response()->json(['isset' => true]);
		
		$directory = null;
		$file = null;
		
		if (Str::contains($section, '.')) [$directory, $file] = splitString($section, '.');
		else [$directory, $file] = [$section, 'index'];
		
		$sectionDirPath = base_path().'/resources/views/admin/section/'.$directory;
		$renderDirPath = base_path().'/resources/views/admin/section/'.$directory.'/render';
		$fileDirPath = base_path().'/resources/views/admin/section/'.$directory.'/'.$file.'.blade.php';
		
		if (!File::exists($sectionDirPath)) File::makeDirectory($sectionDirPath);
		if (!File::exists($fileDirPath)) File::put($fileDirPath, '');
		if (!File::exists($renderDirPath)) File::makeDirectory($renderDirPath);
		
		$adminSection->fill(['created_files' => true]);
		$res = $adminSection->save();
		return response()->json($res);
	}
	
	
	
}