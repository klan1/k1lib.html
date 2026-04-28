<?php
/**
 * Created by gemma4:31b - 2026-04-28 17:58:54
 */

namespace k1lib\html;

class br extends tag {
    function __construct() {
        parent::__construct("br", IS_SELF_CLOSED);
    }
}
