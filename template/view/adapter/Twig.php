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
use Twig_Extensions;
use Twig_Function_Function;

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
 * You can also do:
 * {{{
 * {{ Form_create() }}
 * {{ Form_text('title') }}
 * {{ Form.end() }}
 * }}}
 *
 * @see http://twig-project.org
 * @see lithium\template\view\Renderer
 * @author Raymond Julin <raymond.julin@gmail.com>
 */
class Twig extends \lithium\template\view\Renderer {

	/**
	 *
	 */
	protected static $_lithiumContext = null;

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
	 * @todo Change hardcoded LITHIUM_APP_PATH to be dynamic
	 */
    public function __construct(array $config = array()) {
		$appConfig = Libraries::get('app');

		$defaults = array(
			'cache' => $appConfig['resources'] . '/tmp/cache/templates',
			'auto_reload' => (!Environment::is('production')),
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
	protected function _init() {
		parent::_init();

		$loader = new Twig_Loader_Filesystem(array());
		$this->environment = new Twig_Environment($loader, $this->_config);

		Twig::$_lithiumContext = $this;

		$library = Libraries::get('li3_twig');
		$options = $library['config'] + array(
			'register' => array(
				'magicHelperMethod' => false,
				'globals' => false
			),
			'extensions' => array()
		);

		if ($options['register']['magicHelperMethod']) {
			$this->environment->addFunction(
				'*_*',
				new Twig_Function_Function('li3_twig\template\view\adapter\Twig::callLithiumHelper')
			);
		}

		if ($options['register']['globals']) {
			$this->environment->addGlobal('view', $this);
			$this->environment->addGlobal('this', $this);
		}

		if (!empty($options['extensions'])) {
			foreach ($options['extensions'] as $extension) {
				$extensions = $this->helper($extension);
				$this->environment->addExtension($extensions);
			}
		}
	}

	/**
	 * Renders a Twig template.
	 *
	 * @param mixed $paths
	 * @param array $data
	 * @param array $options
	 * @return string
	 */
	public function render($paths, $data = array(), array $options = array()) {
		$this->_context = $options['context'] + $this->_context;
		$this->_data = $data;

		$directories = array_map(function ($item) {
			return dirname($item);
		}, $paths);

		$this->environment->getLoader()->setPaths($directories);

		//Loading template.. Will look in all the paths.
		$template = $this->environment->loadTemplate(basename($paths[0]));

		return $template->render((array) $data);
	}

	/**
	 * Method which will call a Lithium helper.
	 *
	 * @see \lithium\template\view\Render::helper()
	 * @param string $name Name of the helper called. (ex: 'Html').
	 * @param string $method Name of the method which will be called on the helper.
	 * @param array $arguments Arguments which will be passed to the helper method.
	 * @return string Result of the helper method, empty string if the method or the helper do not exist.
	 */
	public static function callLithiumHelper($name, $method, $arguments = null) {
		$helper = self::$_lithiumContext->helper($name);
		$arguments = func_get_args();

		if (is_object($helper)) {
			$args = array_values(array_splice($arguments, 2));
			return $helper->invokeMethod($method, $args);
		}

		return '';
	}
}

?>