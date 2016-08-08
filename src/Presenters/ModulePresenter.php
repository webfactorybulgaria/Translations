<?php

namespace TypiCMS\Modules\Translations\Presenters;

use TypiCMS\Modules\Core\Shells\Presenters\Presenter;

class ModulePresenter extends Presenter
{
    /**
     * Return name.
     *
     * @return string
     */
    public function title()
    {
        return $this->entity->key;
    }
}
