<?php

namespace TypiCMS\Modules\Translations\Repositories;

use TypiCMS\Modules\Core\Shells\Repositories\CacheAbstractDecorator;
use TypiCMS\Modules\Core\Shells\Services\Cache\CacheInterface;

class CacheDecorator extends CacheAbstractDecorator implements TranslationInterface
{
    public function __construct(TranslationInterface $repo, CacheInterface $cache)
    {
        $this->repo = $repo;
        $this->cache = $cache;
    }

    /**
     * Get translations to Array.
     *
     * @return array
     */
    public function allToArray($locale, $group, $namespace = null)
    {
        $cacheKey = md5(config('app.locale').'TranslationsToArray');

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $data = $this->repo->allToArray($locale, $group, $namespace);

        // Store in cache for next request
        $this->cache->put($cacheKey, $data);

        return $data;
    }

    public function getItemID($key)
    {
        $cacheKey = md5(config('app.locale').'TranslationId'.$key);

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $data = $this->repo->getItemID($key);

        // Store in cache for next request
        $this->cache->put($cacheKey, $data);

        return $data;
    }

    public function updateItem($itemId, $locale, $updatedItem)
    {
        return $this->repo->updateItem($itemId, $locale, $updatedItem);
    }

    public function insertItem($val, $locale, $insertItem)
    {
        return $this->repo->insertItem($val, $locale, $insertItem);
    }

    public function processTranslations($request)
    {
        return $this->repo->processTranslations($request);
    }
}
