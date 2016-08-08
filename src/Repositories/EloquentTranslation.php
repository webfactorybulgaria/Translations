<?php

namespace TypiCMS\Modules\Translations\Repositories;

use DB;
use Illuminate\Database\Eloquent\Model;
use TypiCMS\Modules\Core\Shells\Repositories\RepositoriesAbstract;
use TypiCMS\Modules\Translations\Shells\Models\Translation;
use TypiCMS\Modules\Translations\Shells\Models\TranslationTranslation;

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

    public function getItemID($key) 
    {
        return Translation::where('key', $key)->value('id');
    }

    public function updateItem($itemId, $locale, $updatedItem)
    {

        return TranslationTranslation::where('locale', $locale)
                                     ->where('translation_id', $itemId)
                                     ->update($updatedItem);
    }

    public function processTranslations($request)
    {
        $locale = $request->get('locale') ?: config('app.locale');
        $existingTranslations = $this->allToArray($locale, 'db');

        $lines = preg_split('/\r?\n/', $request->get('translations'));
        $newItems = $itemKeys = [];

        foreach($lines as $line) {
            if(strlen(trim($line)) && strpos($line,'=')) {
                $item = explode('=', $line, 2);
                $itemKey = trim($item[0]);
                $itemVal = trim($item[1]);
                array_push($itemKeys, $itemKey);

                if(isset($existingTranslations[$itemKey])) {
                    if($existingTranslations[$itemKey] != $itemVal) {
                        $this->updateItem($this->getItemID($itemKey), $locale, ['translation' => $itemVal]);
                    }
                }
                else {
                    $newItems[$itemKey] = $itemVal ?: '';
                }
            }
        }

        $this->clearTranslations($itemKeys, $existingTranslations);

        $this->insertTranslations($newItems, $locale);
    }

    public function clearTranslations($itemKeys, $existingTranslations)
    {
        foreach ($existingTranslations as $keycode => $value) {
            if (!in_array($keycode, $itemKeys)) {
                Translation::where('key', $keycode)->first()->delete();
            }
        }
    }

    public function insertTranslations($newItems, $locale)
    {
        foreach ($newItems as $key => $newItem) {
            $itemArray = [
                'group' => 'db',
                'key' => trim($key),
            ];
            $this->insertItem($newItem, $locale, $itemArray);
        }
    }

    public function insertItem($val, $locale, $insertItem)
    {
        $insertId = Translation::insertGetId($insertItem);
        $items = [];

        foreach(config('translatable.locales') as $trans_locale) {
            $item = [
                'translation' => ($trans_locale == $locale && isset($val)) ? trim($val) : '',
                'translation_id' => $insertId,
                'locale' => $trans_locale
            ];
            array_push($items, $item);
        }
        TranslationTranslation::insert($items);
    }
}
