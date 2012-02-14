<?php
/**
 * Li3_twig: Two step Twig renderer for Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_twig\template\view\adapter;

use lithium\core\Libraries;
use lithium\core\Environment;

use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_Filter_Function;

use li3_twig\template\view\adapter\Template;

/**
 * View adapter for Twig templating.
 * Using helpers works like in normal li3 templates
 * {{{
 * {{ this.form.create }}
 * {{ this.form.text('title') }}
 * {{ this.form.select('gender', ['m':'male','f':'female']) }}
 * {{ this.form.end }}
 * }}}
 *
 * @see http://twig-project.org
 * @see lithium\template\view\Renderer
 * @author Raymond Julin <raymond.julin@gmail.com>
 */
class Twig extends \lithium\template\view\Renderer {

	/**
	 * The Twig Environment object.
	 *
	 * @var object
	 */
	public $environment = null;

	/**
	 * Constructor for this adapter - sets relevant default configurations for Twig to be used
	 * when instantiating a new Twig_Environment and Twig_Loader_Filesystem.
	 *
	 * @param array $config Optional configuration directives.
	 *        Please see http://www.twig-project.org/book/03-Twig-for-Developers for all
	 *        available configuration keys and their description.
     *        There are 4 settings that is set
     *        - `cache`: Path to /resources/tmp/cache/templates/ where compiled templates will be stored
     *        - `auto_reload`: If Environment is not production, templates will be reloaded once edited
     *        - `base_template_class`: Overriden to the Template adapter, be carefull with changing this
     *        - `autoescape`: Set to false because the way we inject content is with full html that should not be escaped
	 * @return void
	 */
    public function __construct(array $config = array()) {
		/**
		 * TODO Change hardcoded LITHIUM_APP_PATH to be dynamic
		 */
		$defaults = array(
			'cache' => LITHIUM_APP_PATH . '/resources/tmp/cache/templates',
			'auto_reload' => (!Environment::is('production')),
			'base_template_class' => 'li3_twig\template\view\adapter\Template',
			'autoescape' => false
		);

		parent::__construct($config + $defaults);
	}

	/**
	 * Initialize the necessary Twig objects & attach them to the current object instance.
	 * Attach any configured filters in the lithium app bootstrap to the Twig object.
	 *
	 * @return void
	 */
	public function _init() {
		parent::_init();

		$Loader = new Twig_Loader_Filesystem(array());
		$this->environment = new Twig_Environment($Loader, $this->_config);

		$options = Libraries::get('li3_twig');

		if (!empty($options['config']['filters'])) {
			if (is_string($options['config']['filters'])) {
				$filters = array($options['config']['filters']);
			}
			else {
				$filters = $options['config']['filters'];
			}

			foreach ($filters as $filter) {
				if (strstr($filter, '::') !== false) {
					$this->environment->addFilter('test', new Twig_Filter_Function($filter));
				}
				else {
					$methods = get_class_methods($filter);

					foreach ($methods as $method) {
						$this->environment->addFilter('test', new Twig_Filter_Function($filter.'::'.$method));
					}
				}
			}
		}

		$this->environment->addGlobal('view', &$this);
	}

	/**
	 * Renders a template
	 *
	 * @param mixed $paths
	 * @param array $data
	 * @param array $options
	 * @return string
	 */
	public function render($paths, $data = array(), array $options = array()) {
		$this->_context = $options['context'] + $this->_context;

		$directories = array_map(function ($item) {
			return dirname($item);
		}, $paths);

		$this->environment->getLoader()->setPaths($directories);

		//Loading template.. Will look in all the paths.
		$template = $this->environment->loadTemplate(basename($paths[0]));

		//Because $this is not available in the Twig template view is used as a substitute.
		return $template->render((array) $data + array('this' => $this));
	}
}

?>