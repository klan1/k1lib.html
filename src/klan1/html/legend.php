<?php

namespace k1html\html;

class legend extends tag {

    use append_shotcuts;

    function __construct($value) {
        parent::__construct("legend", FALSE);
        $this->set_value($value);
    }
}
