<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\Interfaces\SystemRepositoryInterface as SystemRepository;
use App\Models\Language;

class SystemComposer
{
    protected $language;

    public function __construct(SystemRepository $systemRepository)
    {
        $this->language = app()->getLocale();
        $this->systemRepository = $systemRepository;
    }

    public function compose(View $view)
    {
        // dd($this->language);
        $language = Language::where('canonical', $this->language)->first();
        $condition = [
            ['language_id', '=', $language->id],
        ];
        $systems=$this->systemRepository->findByConditions($condition);
        // dd($systems);
        $systems = convert_array($systems, 'keyword', 'content');
        // dd($systems);

        $view->with('systems', $systems);
    }
}