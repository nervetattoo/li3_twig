# li3_twig

Enables Twig support for Lithium PHP Framework.

## Usage

First enable the plugin with

```php
<?php
Libraries::add('li3_twig', array('bootstrap' => true));
```

Second, make sure that your rendering process is set to render the new Twig media type:

```php
<?php
$this->_render['type'] = 'twig';
```

From your controller, return arrays (as you normally would) with properties that should be accessible in the Twig template.

Access your fields like this in the template later.

```jinja
<h1>Hello {{ name }}</h1>
```

Note that the `this` is an automatically added reference to the Environment and under it you can lazy load
helpers like in regular lithium templates.

```jinja
{{ this.form.create }}
{{ this.form.text('title') }}
{{ this.form.select('gender', {'m':'male','f':'female'}) }}
{{ this.form.end }}
```

### Note about Lithium helpers

If you override or make your own helper functions, make sure to always end your functions by returning a value. If you don't want to output anything, just return an empty string.

## Thanks

This library is a fork from nervetattoo's github repository.