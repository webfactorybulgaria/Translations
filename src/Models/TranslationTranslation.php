<?php

namespace TypiCMS\Modules\Translations\Models;

use TypiCMS\Modules\Core\Custom\Models\BaseTranslation;

class TranslationTranslation extends BaseTranslation
{
    /**
     * get the parent model.
     */
    public function owner()
    {
        return $this->belongsTo('TypiCMS\Modules\Translations\Custom\Models\Translation', 'translation_id')->withoutGlobalScopes();
    }
}
