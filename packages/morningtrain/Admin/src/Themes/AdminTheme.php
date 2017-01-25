<?php

namespace morningtrain\Admin\Themes;

use morningtrain\Admin\Helpers\Translation;
use morningtrain\Themer\Contracts\Theme;

class AdminTheme extends Theme {

    protected function register() {
        parent::register();
    }

    /*
     * Menu status
     */

    public function getMenuStatus( string $slug, $default = null ) {
        $cookie = $slug . '_menu_status';
        return isset($_COOKIE[$cookie]) ? $_COOKIE[$cookie] : $default;
    }

    /*
     * Main menu items
     */

    protected $mainMenuItems;

    public function getMainMenuItems() {

        if (!isset($this->mainMenuItems)) {
            // Get registered crud models
            $models = config('admin.items', []);

            // Prepare items array
            $this->mainMenuItems = [];

            foreach ($models as $model => $params) {

                if (is_int($model)) {
                    $model = $params;
                    $params = [];
                }

                $item = new \stdClass();
                $item->slug = (new $model)->getPluralName();
                $item->basepath = route('admin.' . $item->slug . '.index');     // Have to fix this when index is not ''
                $item->path = route('admin.' . $item->slug . '.index');
                $item->label = Translation::get('crud.' . $item->slug . '.label', [], ucfirst($item->slug));
                $item->params = $params;

                $this->mainMenuItems[] = $item;
            }
        }

        return $this->mainMenuItems;
    }

}