<?php

namespace k1html\html\bootstrap;

use k1html\html\div;
use k1html\html\input as input_tag;

class input_text_with_icon extends div {

    use bootstrap_methods;

    private input_tag $input;

    public function __construct($name, $value, $icon = null, $position = 'left') {
        parent::__construct('form-group position-relative has-icon-' . $position);

        $this->input = new input_tag('text', $name, $value, 'form-control');
        $this->input->append_to($this);

        $this->append_div('form-control-icon')->append_i(NULL, $icon);
        $this->link_value_obj($this->input);
    }

    public function input(): input_tag {
        return $this->input;
    }
}
