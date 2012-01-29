<?php

namespace li3_twig\template;

use lithium\template\View as LithiumView;

class View extends LithiumView {
	protected $_processes = array(
		'all' => array('template'),
		'template' => array('template'),
		'element' => array('element')
	);
	protected $_steps = array(
		'template' => array('path' => 'template', 'capture' => array('context' => 'content')),
		'element' => array('path' => 'element')
	);
}

?>