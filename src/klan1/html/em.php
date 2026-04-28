<?php
/**
 * Created by gemma4:31b - 2026-04-28 17:58:54
 */

namespace k1lib\html;

class em extends tag {
    use append_shotcuts;
    function __construct($class = NULL, $id = NULL) {
        parent::__construct("em", IS_NOT_SELF_CLOSED);
        $this->set_class($class, TRUE);
        $this->set_id($id);
    }
}
