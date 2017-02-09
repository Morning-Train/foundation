<?php

namespace morningtrain\Themer\Extensions;

trait HasAssets
{

    /**
     * @var array
     */
    protected $scripts;

    /**
     * @var array
     */
    protected $stylesheets;

    /**
     * @var array
     */
    protected $localization;

    /*
     * Scripts
     */

    public function addScript($script)
    {
        $this->scripts[] = $script;

        return $this;
    }

    /*
     * Stylesheets
     */

    public function addStylesheet($style)
    {
        $this->stylesheets[] = $style;

        return $this;
    }

    /*
     * Localization
     */

    public function localize(array $data)
    {
        $this->localization = array_merge_recursive($this->localization, $data);

        return $this;
    }

    /*
     * Registration
     */

    protected function registerAssets()
    {
        // Initialization
        if (!isset($this->scripts)) {
            $this->scripts = [];
        }

        if (!isset($this->stylesheets)) {
            $this->stylesheets = [];
        }

        if (!isset($this->localization)) {
            $this->localization = [];
        }

        // Register default theme assets
        $this->addStylesheet(asset('themes/' . $this->slug . '/app.css'));
        $this->addScript(asset('themes/' . $this->slug . '/app.js'));

        // Register actions
        $this->addAction('head', [$this, 'printStylesheets']);
        $this->addAction('footer', [$this, 'printLocalization']);
        $this->addAction('footer', [$this, 'printScripts']);
    }

    /*
     * Action callbacks
     */

    protected function printStylesheets()
    {
        foreach ($this->stylesheets as $src) {
            echo '<link rel="stylesheet" href="' . $src . '"/>';
        }
    }

    protected function printScripts()
    {
        foreach ($this->scripts as $src) {
            echo '<script type="text/javascript" src="' . $src . '"></script>';
        }
    }

    protected function printLocalization()
    {
        if (count($this->localization) === 0) {
            return '';
        }

        $html = '<script type="text/javascript">';

        foreach ($this->localization as $key => $value) {
            $html .= "window.$key=" . json_encode($value) . ";";
        }

        $html .= '</script>';

        echo $html;
    }

}