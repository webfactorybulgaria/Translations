<?php

namespace TypiCMS\Modules\Translations\Repositories;

use DB;
use Illuminate\Database\Eloquent\Model;
use TypiCMS\Modules\Core\Repositories\RepositoriesAbstract;

class EloquentTranslation extends RepositoriesAbstract implements TranslationInterface
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get translations to Array.
     *
     * @return array
     */
    public function allToArray($locale, $group, $namespace = null)
    {
        $array = DB::table('translations')
                ->select('translation', 'key')
                ->join('translation_translations', 'translations.id', '=', 'translation_translations.translation_id')
                ->where('locale', $locale)
                ->where('group', $group)
                ->orderBy('key')
                ->pluck('translation', 'key');

        return $array;
    }

    public function deleteAll($locale)
    {
        return DB::table('translation_translations')
                 ->where('locale', $locale)
                 ->delete();
    }

    public function getItemID($key) 
    {
        return DB::table('translations')
                 ->where('key', $key)
                 ->value('id');
    }

    public function insertMassItems($massItems) 
    {
        return DB::table('translation_translations')->insert($massItems);
    }

    public function deleteEmptyItems()
    {
        $trans_keys = DB::table('translations')
                        ->select('id')
                        ->pluck('id');
        foreach ($trans_keys as $id)
        {
            $translations = DB::table('translation_translations')
                            ->select('translation')
                            ->where('translation_id', $id)
                            ->pluck('translation');
            $allLocalesEmpty = true;
            foreach ($translations as $translation)
            {
                if (!empty(trim($translation))) 
                    $allLocalesEmpty = false;
                break;
            }
            if ($allLocalesEmpty){
                //dd('x');
                DB::table('translations')->where('id', $id)->delete();
            }
        }
    }
}
