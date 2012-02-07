<?php
use lithium\core\Libraries;
use lithium\net\http\Media;

/**
 * This is the path to the li3_twig plugin, used for Libraries path resolution.
 */
define('LI3_TWIG_PATH', dirname(__DIR__));


/**
 * Add the Twig libraries
 */
Libraries::add('Twig', array(
	'path' => LI3_TWIG_PATH . '/libraries/Twig/lib/Twig',
	'prefix' => 'Twig_',
	'loader' => 'Twig_Autoloader::autoload',
));

/**
 * Make Twig the default renderer for View.
 * If you want to keep the old two process rendering but use Twig for templating,
 * comment the following and use the second Media::type under it.
 */
Media::type('default', null, array(
	'view' => 'li3_twig\template\View',
	'loader' => 'li3_twig\template\Loader',
	'renderer' => 'li3_twig\template\view\adapter\Twig',
	'paths' => array(
		'template' => array(
			'{:library}/views/{:controller}/{:template}.{:type}.twig',
			'{:library}/views/layouts'
		)
	)
));

// Media::type('default', null, array(
// 	'view' => 'lithium\template\View',
// 	'loader' => 'li3_twig\template\Loader',
// 	'renderer' => 'li3_twig\template\view\adapter\Twig',
// 	'paths' => array(
// 		'template' => '{:library}/views/{:controller}/{:template}.{:type}.twig',
// 		'layout' => '{:library}/views/layouts/{:layout}.{:type}.twig'
// 	)
// ));

?>