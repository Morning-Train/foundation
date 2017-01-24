<?php

namespace morningtrain\Admin\Extensions;

trait HasAvatar {

    public function hasAvatar() {
        return isset($this->avatar) && is_string($this->avatar) && (strlen($this->avatar) > 0);
    }

}