<?php namespace App\Models\System;

use App\Models\Traits\Collectionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class AdminSection extends Model {
    use HasFactory, Collectionable;
	
	/**
     * Таблица
	 *
     * @var string
     */
	protected $table = 'admin_sections';
	
	
	/**
     * Раздел аутентификации
	 *
     * @var string
     */
	protected $guard = 'admin';
	
	
	/**
     * Поля разрешенные для редактирования
	 *
     * @var array
     */
	protected $fillable = [
		'section',
		'page_title',
		'title',
		'parent_id',
		'visible',
		'nav',
		'created_files',
		'sort',
		'_sort',
	];
	
	
	/**
     * учитывать временные поля created_at и updated_at
	 *
     * @var string
     */
	public $timestamps = false;
	
	
	
	
	/**
     * Атрибуты, которые должны быть типизированы. (Конвертация полей при добавлении и получении)
	 *
     * @var array
     */
	protected $casts = [
        'title' => 'array',
        'page_title' => 'array',
    ];
	
	
	
	
	/**
     * Дополнительные поля
	 *
     * @var array
     */
	protected $appends = [
        //'page_title_lang'
    ];
	
	
	
	
	
	
	
	/**
	 * @param string  $value
	 * @return 
	 */
	public function setTitleAttribute($value) {
		$this->attributes['title'] = is_array($value) ? json_encode($value) : $value;
	}
	
	/**
	 * @param string  $value
	 * @return 
	 */
	public function setPageTitleAttribute($value) {
		$this->attributes['page_title'] = is_array($value) ? json_encode($value) : $value;
	}
	
	
	/**
     * 
     * @param string  $value
     * @return string
     */
    public function setParentIdAttribute($value = null) {
		$this->attributes['parent_id'] = is_null($value) ? 0 : $value;
	}
	
	
	
	/**
     * 
     * @param string  $value
     * @return string
     */
    public function getTitleAttribute($value) {
		$value = isJson($value) ? json_decode($value, true) : $value;
		return $value ?? null;
	}
	
	/**
     * 
     * @param string  $value
     * @return string
     */
    public function getPageTitleAttribute($value) {
		$value = isJson($value) ? json_decode($value, true) : $value;
		return $value ?? null;
	}
	
	
	
	
	/**
	 * @param string  $activeNav
	 * @return Collection
	 */
	public function getSections($activeNav = false): Collection {
		$navData = collect([]);
		$lang = App::currentLocale();
		
		$sections = $this->select('id', 'section', 'title', 'parent_id', 'nav')
						->where('visible', 1)
						->orderBy('sort', 'ASC')
						->get()
						->filter(function ($item) {
							if (auth('admin')->check() && auth('admin')->user()->is_main_admin) return true;
							return auth('admin')->check() && auth('admin')->user()->can('section-'.$item['section'].':admin');
						})->map(function($item) use($lang) {
							$item['title'] = $item['title'][$lang] ?? $item['title'][App::getFallbackLocale()];
							return $item;
						});
						
			
		if ($sections->isEmpty()) return $navData;
		
		$groupedSections = $sections->groupBy('parent_id');
		
		if ($groupedSections->get(0)->isEmpty()) return $navData;
		
		$navData = $groupedSections->get(0)->each(function ($item, $key) use($groupedSections, $activeNav) {
			$children = $groupedSections->get($item['id']);
			if ($children) $item['children'] = $children->toArray();
			if ($activeNav == $item['section']) $item['active'] = true;
		});
		
		return $navData;
	}
	
	
	
	
	
}