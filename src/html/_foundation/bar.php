<?php

namespace k1html\html\foundation;

class bar extends \k1html\html\div {

    /**
     * @var string
     */
    protected $type;

    /**
     * @var \k1html\html\div
     */
    protected $left = null;

    /**
     * @var \k1html\html\div
     */
    protected $right = null;

    function __construct($type, $id = NULL) {
        $this->type = $type;
        parent::__construct("{$type}-bar", $id);
        $this->left = new \k1html\html\div("{$type}-bar-left");
        $this->right = new \k1html\html\div("{$type}-bar-right");

        $this->left->append_to($this);
        $this->right->append_to($this);
    }

    /**
     * @return \k1html\html\div
     */
    public function left() {
        return $this->left;
    }

    /**
     * @return \k1html\html\div
     */
    public function right() {
        if (empty($this->right)) {
            $this->right = new \k1html\html\div("{$this->type}-bar-right");
        }
        return $this->right;
    }
}
