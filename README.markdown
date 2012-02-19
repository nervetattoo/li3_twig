# li3_twig

Enables Twig support for Lithium PHP Framework.

## Installation

1. Clone the repository in your lithium libraries folder.
2. Init and update the git submodules to retreive a copy of Twig.

## Usage

### Configuration

1. First enable the plugin with

```php
<?php
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

2. Second, make sure that your rendering process is set to render the new Twig media type:

```php
<?php
$this->_render['type'] = 'twig';
?>
```

From your controller, return arrays (as you normally would) with properties that should be accessible in the Twig template.

Access your fields like this in the template later.

```jinja
<h1>Hello {{ name }}</h1>
```

### Lithium helpers

Note that the `this` is an automatically added reference to the Environment and under it you can lazy load
helpers like in regular lithium templates.

```jinja
{{ this.helper('Form').create }}
{{ this.helper('Form').text('title') }}
{{ this.helper('Form').select('gender', {'m':'male','f':'female'}) }}
{{ this.helper('Form').end }}
```

For even more Twig and Lithium love, you can use the dynamic `*_*` that will call lithium helpers automatically. The form creation above can be translated in the following.

```jinja
{{ Form_create }}
{{ Form_text('title') }}
{{ Form_select('gender', {'m':'male','f':'female'}) }}
{{ Form_end }}
```

### Note about Lithium helpers

If you override or make your own helper functions, make sure to always end your functions by returning a value. If you don't want to output anything, just return an empty string.

### Note about Twig extensions

Todo...

## Thanks

This library is a fork from nervetattoo's github repository.