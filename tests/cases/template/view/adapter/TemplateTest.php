<?php
/**
 * Li3_twig: Two step Twig renderer for Lithium: the most rad php framework
 *
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_twig\tests\cases\template\view\adapter;

use li3_twig\tests\mocks\template\view\adapter\MockTemplate;
use \Twig_Environment;
use \Twig_Loader_Filesystem;

/**
 * Test that rendering through a mocked template works.
 * `Twig_Template::getAttribute` is overridden so the fact that it is working must be proved
 *
 * @see http://twig-project.org
 * @author Raymond Julin <raymond.julin@gmail.com>
 */

class TemplateTest extends \lithium\test\Unit {

	public function testConstruct() {
		$Loader = new Twig_Loader_Filesystem(array());
		$environment = new Twig_Environment($Loader, array());
        $environment->initRuntime();
        $template = new MockTemplate($environment);
		$this->assertTrue($template instanceof MockTemplate);

        $bar = "bar";
        $foo = new \stdClass;
        $foo->bar = $bar;

        $output = $template->render(compact('foo'));
		$this->assertEqual($bar, $output);
	}
}

?>
