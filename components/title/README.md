# Title Component

This is a component that provides a configurable title element with various style and size options. It allows for
consistent title styling across your Drupal site while maintaining flexibility for different contexts.

## Usage

You can use this component in your Twig templates or PHP render arrays:

### In Twig:

```twig
{{ include('sillage_fields:title',{
  text: 'Your Title Text',
  style: 'primary',
  size: 'h2',
  attributes: {
    'id': 'my-title',
    'data-custom': 'value'
  }
}}
```

### In PHP:

```php
$build['title'] = [
  '#type' => 'component',
  '#component' => 'sillage_fields:title',
  '#props' => [
    'text' => 'Your Title Text',
    'style' => 'primary',
    'size' => 'h2',
    'attributes' => [
      'id' => 'my-title',
      'data-custom' => 'value',
    ],
  ],
];
```

## Properties

| Name       | Type   | Description                                      | Examples                                      |
|------------|--------|--------------------------------------------------|-----------------------------------------------|
| text       | string | The text to display as a title                   | "Page Title", "Section Heading"               |
| style      | string | The visual style of the title                    | primary, secondary, accent                    |
| size       | string | The size of the title (heading level)            | h1, h2, h3, h4, h5                            |
| attributes | object | Additional HTML attributes for the title element | {'id': 'main-title', 'class': 'custom-class'} |

## Examples

### Primary H1 Title

```twig
{{ component('title', {
  text: 'Welcome to Our Site',
  style: 'primary',
  size: 'h1'
}) }}
```

### Secondary H3 Title

```twig
{{ component('title', {
  text: 'Latest Articles',
  style: 'secondary',
  size: 'h3'
}) }}
```

### Accent H2 Title with Custom ID

```twig
{{ component('title', {
  text: 'Featured Content',
  style: 'accent',
  size: 'h2',
  attributes: {
    'id': 'featured-section'
  }
}) }}
```

### Small H5 Title

```twig
{{ component('title', {
  text: 'Additional Information',
  style: 'primary',
  size: 'h5'
}) }}
```

## Integration with Title Field Type

This component is designed to work seamlessly with the Title Field module. When using the Title Field formatter, it will
automatically map field properties to component properties:

- Field **text** → Component `text`
- Field **style** (primary, secondary, accent) → Component `style`
- Field **size** (h1-h5) → Component `size`

## Customization

To customize the appearance of the title styles, override the component in your theme by:

1. Creating a matching component in your theme's components directory
2. Implementing your own CSS for the different styles and sizes
3. Extending the base component template as needed
