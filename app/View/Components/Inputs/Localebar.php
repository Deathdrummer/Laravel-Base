<?php namespace App\View\Components\inputs;

use App\Traits\HasComponent;
use Illuminate\Support\Facades\App;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Localebar extends Component {
    use HasComponent;
	
	public $locales = [];
	public $currentLocale;
	
	/**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct() {
		$locales = setting('locales_list');
		
		if ($locales) {
			$this->locales = $locales->mapWithKeys(function($item) {
				if (!isset($item['abbr'])) $item['abbr'] = $item['locale'];
				if (!isset($item['title'])) $item['title'] = $item['locale'];
				return [$item['locale'] => $item];
			})->toArray();
		} else {
			foreach(config('app.locales_list') as $locale) {
				$this->locales[$locale] = [
					'locale'	=> $locale,
					'abbr'		=> Str::upper($locale),
					'title'		=> $locale,
				];
			}
		}
		
		if (!in_array(App::currentLocale(), array_keys($this->locales))) App::setLocale(App::getFallbackLocale());
		
		$this->currentLocale = App::currentLocale();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render() {
        return view('components.inputs.localebar');
    }
}