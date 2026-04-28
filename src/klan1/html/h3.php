<?php

namespace k1html\html;

/**
 * h3
 */
class h3 extends tag {

    use append_shotcuts;

    function __construct($value = NULL, $class = NULL, $id = NULL) {
        parent::__construct("h3", FALSE);
        $this->set_value($value);
        $this->set_class($class);
        $this->set_id($id);
    }
}
