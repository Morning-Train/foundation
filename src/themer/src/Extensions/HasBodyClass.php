<?php

namespace morningtrain\Themer\Extensions;

trait HasBodyClass
{
    use HasConfig;

    public function setBodyClass($class, $replace = false)
    {
        $current = $replace ? [] : $this->get('body.class', []);

        if (!is_array($current)) {
            $current = [$current];
        }

        if (!is_array($class)) {
            $class = [$class];
        }

        foreach ($class as $bodyClass) {
            if (!in_array($bodyClass, $current)) {
                $current[] = $bodyClass;
            }
        }

        $this->set('body.class', $current);

        return $this;
    }

    protected function registerBodyClass()
    {
        $this->addAction('bodyClass', [$this, 'printBodyClass']);
    }

    protected function printBodyClass($extras = null, $replace = false)
    {
        // Push extras
        if (!is_null($extras)) {
            $this->setBodyClass($extras, $replace);
        }

        // Push current route identifier
        $routeAction = request()->route()->getAction();

        if (isset($routeAction['as'])) {
            $routeClass = preg_replace('/[^a-zA-Z0-9\-_]+/', '-', $routeAction['as']);
            $this->setBodyClass($routeClass);
        }

        // Print
        echo implode(' ', $this->get('body.class', []));
    }

}