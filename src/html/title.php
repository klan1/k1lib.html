<?php

namespace k1html\html;

class title extends tag {

    use append_shotcuts;

    function __construct() {
        parent::__construct("title", FALSE);
    }
}
