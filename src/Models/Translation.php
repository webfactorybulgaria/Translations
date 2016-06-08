<?php

namespace TypiCMS\Modules\Translations\Models;

use Laracasts\Presenter\PresentableTrait;
use TypiCMS\Modules\Core\Models\Base;
use TypiCMS\Modules\Core\Traits\Translatable;
use TypiCMS\Modules\History\Traits\Historable;

class Translation extends Base
{
    use Historable;
    use PresentableTrait;
    use Translatable;

    protected $presenter = 'TypiCMS\Modules\Translations\Presenters\ModulePresenter';

    protected $fillable = [
        'group',
        'key',
        // Translatable columns
        'translation',
    ];

    /**
     * Translatable model configs.
     *
     * @var array
     */
    public $translatedAttributes = [
        'translation',
    ];

    protected $appends = [];

}
