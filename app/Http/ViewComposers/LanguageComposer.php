<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;

class LanguageComposer
{
    protected $language;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    public function compose(View $view)
    {
        $condition = [
            ['publish', '=', 2],
        ];
        $order = ['current', 'desc'];
        $languages=$this->languageRepository->findByConditionsWithOrder($condition, $order);
        // dd($languages);

        $view->with('languages', $languages);
    }
}