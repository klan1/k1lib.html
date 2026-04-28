<?php

namespace k1html\html\foundation;

class title_bar extends bar {

    /**
     * @var \k1html\html\button
     */
    protected $left_button = null;

    /**
     * @var \k1html\html\span
     */
    protected $title = null;

    function __construct($id = NULL) {
        parent::__construct('title', $id);
        $this->left_button = new \k1html\html\button(NULL, "menu-icon");
        $this->left_button->append_to($this->left);

        $this->title = new \k1html\html\span("title-bar-title");
        $this->title->append_to($this->left);
    }

    /**
     * @return \k1html\html\span
     */
    public function title() {
        return $this->title;
    }

    /**
     * @return \k1html\html\button
     */
    public function left_button() {
        return $this->left_button;
    }
}
