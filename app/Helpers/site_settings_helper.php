<?php

use App\Models\SiteSettingModel;

if (!function_exists('site_setting')) {
    /**
     * Fetch a site setting value by key.
     */
    function site_setting(string $key, ?string $default = null): ?string
    {
        static $cache = [];

        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        try {
            $model = new SiteSettingModel();
            $cache[$key] = $model->getValue($key, $default);
        } catch (Throwable $e) {
            // If DB isn't ready (e.g., migrations not run yet), fail safe.
            $cache[$key] = $default;
        }

        return $cache[$key];
    }
}

if (!function_exists('site_setting_set')) {
    /**
     * Persist a site setting key/value.
     */
    function site_setting_set(string $key, ?string $value): bool
    {
        try {
            $model = new SiteSettingModel();
            return $model->setValue($key, $value);
        } catch (Throwable $e) {
            return false;
        }
    }
}
