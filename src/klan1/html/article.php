<?php

namespace k1lib\html;

class article extends tag {

    use append_shotcuts;

    /**
     * Create an ARTICLE html tag with VALUE as data.
     * @param string $class
     * @param string $id
     */
    function __construct($class = NULL, $id = NULL) {
        parent::__construct("article", IS_NOT_SELF_CLOSED);
        $this->set_class($class, TRUE);
        $this->set_id($id);
    }
}
