<?php namespace App\Exports;

use App\Exports\Sheets\EventTypeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EventsExport implements WithMultipleSheets {
    use Exportable;
    
    public function __construct() {
    }

    /**
     * @return array
     */
    public function sheets(): array {
        $sheets = [];
		
		$listsNames = [
			'new' 			=> 'Новый',
			'wait' 			=> 'Ожидание',
			'cancel' 		=> 'Отмененный',
			'ready' 		=> 'Готов',
			'doprun' 		=> 'Допран',
			'wait_nosort' 	=> 'Ожидание (Н)',
			'cancel_nosort'	=> 'Отмененные (Н)',
			'all' 			=> 'Вообще все',
		];
		
		foreach ($listsNames as $status => $listName) {
			$sheets[] = new EventTypeSheet($status, $listName);
		}

        return $sheets;
    }
}