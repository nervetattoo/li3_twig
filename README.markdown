# li3_twig

Enables [Twig](http://twig.sensiolabs.org/) support for the [Lithium PHP Framework](https://github.com/UnionOfRAD/lithium).

## Installation

1. Clone the repository in your lithium libraries folder.
2. Init and update the git submodules to retreive a copy of Twig.

## Usage

### Configuration

- First enable the library inside your Lithium app by adding the following to your `bootstrap/libraries.php` file:

```
Libraries::add('li3_twig', array(
	'config' => array(
		'register' => array(
			'globals' =>  true, // Makes the view available inside Twig templates throug the `this` and `view` globals.
			'magicHelperMethod' => true // Makes lithium helpers available through a magic method. Html_link will call the link method of the Html helper.
		),
		'extensions' => array('TwigExtensions') // Twig extensions.
	)
));
?>
```

- Second, make sure that your rendering process is set to render the new Twig media type:

```
$this->_render['type'] = 'twig';
```

From your controller, return arrays (as you normally would) with properties that should be accessible in the Twig template.

Access your fields like this in the template later.

```
Hello {{ name }}
```

### Lithium helpers

Note that the `this` is an automatically added reference to the Environment and under it you can lazy load
helpers like in regular lithium templates.

```
{{ this.helper('Form').create }}
{{ this.helper('Form').text('title') }}
{{ this.helper('Form').select('gender', {'m':'male','f':'female'}) }}
{{ this.helper('Form').end }}
```

For even more Twig and Lithium love, you can use the dynamic `*_*` that will call lithium helpers automatically. The form creation above can be translated in the following.

```
{{ Form_create() }}
{{ Form_text('title') }}
{{ Form_select('gender', {'m':'male','f':'female'}) }}
{{ Form_end() }}
```

#### Note about Lithium helpers

If you override or make your own helper functions, make sure to always end your functions by returning a string! If you don't want to output anything, just return an empty string. Twig does not like to ouput `null` in its templates, it will usually throw an error at you and electronic sheeps will rule your desktop - true story (well, not really…).

### Twig extensions

The `extensions` field in the library configuration defines a list of class that will be handled as [Twig Extensions](http://twig.sensiolabs.org/doc/advanced.html#creating-an-extension) by the Twig adapter. Please refer to the Twig documentation if you wish to create your own extensions.

### CLI commands

I have included a CLI command to erase all twig cache at once.

Twig will create a php file representing your twig template. This file will be refreshed automatically if you update your template in a development environment. However, if you are in production mode, these twig files are not updated automatically for performance reasons. Checking for updates is costly, and compiling your template even more.

Taking into account that the li3 is in your `PATH` variable, the command would go something like this: `li3 Twig flush`.

Remember to execute this every time your production files are updated.

## Thanks

This library is a fork from nervetattoo's github repository. Thank's to him for putting me on the right track with his library!
Thanks to the Lithium framework and to all of its contributors! Their framework is kind of the only reason I continue to use php today :)