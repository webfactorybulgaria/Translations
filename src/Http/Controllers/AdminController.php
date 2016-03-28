<?php

namespace TypiCMS\Modules\Translations\Http\Controllers;

use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Translations\Http\Requests\FormRequest;
use Illuminate\Http\Request;
use TypiCMS\Modules\Translations\Models\Translation;
use TypiCMS\Modules\Translations\Repositories\TranslationInterface;
use Illuminate\Support\Facades\Input;

class AdminController extends BaseAdminController
{
    public function __construct(TranslationInterface $translation)
    {
        parent::__construct($translation);
    }

    /**
     * Create form for a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = $this->repository->getModel();

        return view('core::admin.create')
            ->with(compact('model'));
    }

    /**
     * Edit form for the specified resource.
     *
     * @param \TypiCMS\Modules\Translations\Models\Translation $translation
     *
     * @return \Illuminate\View\View
     */
    public function edit(Translation $translation)
    {
        return view('core::admin.edit')
            ->with(['model' => $translation]);
    }

    /**
     * Edit form for all the resources.
     *
     * @param \TypiCMS\Modules\Translations\Models\Translation $translation
     *
     * @return \Illuminate\View\View
     */
    public function massEdit()
    {
        $locale = Input::get('locale');
        $locale = $locale ?: 'en';

        $models = $this->repository->allToArray($locale, 'db');

        return view('translations::admin.mass')
            ->with(['models' => $models]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \TypiCMS\Modules\Translations\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function massStore(Request $request)
    {
        $locale = Input::get('locale');
        $locale = $locale ?: 'en';

        $oldTranslations = $this->repository->allToArray($locale,'db');
        $translations = $request->get('translations');
        $lines = preg_split('/\r?\n/', $translations);
        //$this->repository->deleteAll($locale);
        $insertItems = $newItems = [];
        //dd($lines);
        foreach ($lines as $line) {
            if(strlen(trim($line))) {
                
                $item = explode('=', $line, 2);
                $updateItems = $insertItems[];
                if (isset($oldTranslations[trim($item[0])])) {
                    if ($oldTranslations[trim($item[0])] != trim($item[1]))
                        $updateItems[trim($item[0])] = trim($item[1]);
                }
                else {
                    $insertItems[trim($item[0])] = $item[1]; 
                }


                /*$trans_id = $this->repository->getItemID(trim($item[0]));
                
                if (isset($trans_id)) {
                    $insertItems[trim($item[0])] = array(
                        'translation_id' => $trans_id, 
                        'locale' => $locale, 
                        'translation' => trim($item[1]),
                    );
                }
                else {
                    array_push($newItems, $item);
                }*/
            }
        }
        //dd($updateItems);
        //dd($newItems);
        /*foreach ($newItems as $newItem) {
            $itemArray = array(
                            'id' => '',
                            'group' => 'db',
                            'key' => trim($newItem[0]),
                        );
            foreach(config('translatable.locales') as $trans_locale) {
                $itemArray[$trans_locale] = array('translation' => ($trans_locale == $locale && isset($newItem[1])) ? trim($newItem[1]) : '');
            }
            //dd($itemArray);
            $this->repository->create($itemArray);
        }*/
        
        //$this->repository->insertMassItems($insertItems);
        //$this->repository->deleteEmptyItems();

        return redirect()->route('admin.translations.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \TypiCMS\Modules\Translations\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FormRequest $request)
    {
        $translation = $this->repository->create($request->all());

        return $this->redirect($request, $translation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \TypiCMS\Modules\Translations\Models\Translation        $translation
     * @param \TypiCMS\Modules\Translations\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Translation $translation, FormRequest $request)
    {
        $this->repository->update($request->all());

        return $this->redirect($request, $translation);
    }
}
