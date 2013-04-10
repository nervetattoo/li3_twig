# DEPRECATED

Make sure you use the [official lithium twig plugin](http://github.com/unionofrad/li3_twig) instead of this outdated version.

### Old docs

First enable the plugin with 

```php
<?php
Libraries::add('li3_twig', array('bootstrap' => true));
```

From your controller return arrays with properties that should be accessible in the Twig template.
Access your fields like this in the template later.
Note that the `this` is an automatically added reference to the Environment and under it you can lazy load
helpers like in regular lithium templates.

```jinja
<h1>Hello {{ name }}</h1>
{{ this.form.create }}
{{ this.form.text('title') }}
{{ this.form.select('gender', {'m':'male','f':'female'}) }}
{{ this.form.end }}
```
