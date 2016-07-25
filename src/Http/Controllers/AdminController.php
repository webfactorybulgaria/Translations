<?php

namespace TypiCMS\Modules\Translations\Http\Controllers;

use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Translations\Http\Requests\FormRequest;
use Illuminate\Http\Request;
use TypiCMS\Modules\Translations\Models\Translation;
use TypiCMS\Modules\Translations\Repositories\TranslationInterface;
use Illuminate\Support\Facades\Input;
use Notification;
use TypiCMS\Modules\Translations\Services\Translations;

class AdminController extends BaseAdminController
{
    public function __construct(TranslationInterface $translation)
    {
        parent::__construct($translation);
    }

    /**
     * List models.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        return view('translations::admin.index');
    }

    /**
     * Create form for a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = $this->repository->getModel();

        return view('translations::admin.create')
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
        return view('translations::admin.edit')
            ->with(['model' => $translation]);
    }

    /**
     * Edit form for all the resources.
     *
     * @return \Illuminate\View\View
     */
    public function massEdit()
    {
        $locale = Input::get('locale') ?: config('app.locale');

        $models = $this->repository->allToArray($locale, 'db');

        return view('translations::admin.mass')->with(['models' => $models]);
    }

    /**
     * Store all newly created resources in storage.
     *
     * @param \TypiCMS\Modules\Translations\Http\Requests\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function massStore(Request $request)
    {
        $this->repository->processTranslations($request);

        Notification::success(trans('db.mass_edit_success'));

        return Input::get('exit') ? redirect()->route('admin::index-translations') : redirect()->back();
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
