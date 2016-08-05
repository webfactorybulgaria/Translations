<?php

namespace TypiCMS\Modules\Translations\Models;

use Laracasts\Presenter\PresentableTrait;
use TypiCMS\Modules\Core\Custom\Models\Base;
use TypiCMS\Modules\Core\Custom\Traits\Translatable;
use TypiCMS\Modules\History\Custom\Traits\Historable;

class Translation extends Base
{
    use Historable;
    use PresentableTrait;
    use Translatable;

    protected $presenter = 'TypiCMS\Modules\Translations\Custom\Presenters\ModulePresenter';

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
