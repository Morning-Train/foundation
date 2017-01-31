<?php

namespace morningtrain\Acl\Extensions;

trait HasDisplayName
{

    protected function getDisplayNameAttribute()
    {
        if (isset($this->name) && is_string($this->name) && (strlen($this->name) > 0)) {
            return $this->name;
        }

        return ucfirst($this->slug);
    }

}