<?php

namespace k1lib\html;

class footer extends tag {

    use append_shotcuts;

    /**
     * Create a FOOTER html tag with VALUE as data.
     * @param string $class
     * @param string $id
     */
    function __construct($class = NULL, $id = NULL) {
        parent::__construct("footer", IS_NOT_SELF_CLOSED);
        $this->set_class($class, TRUE);
        $this->set_id($id);
    }
}
