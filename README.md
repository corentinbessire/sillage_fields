# Sillage Fields Module

## Description
This module provides custom field types specifically designed to enhance functionality when used with the Sillage theme framework. It includes specialized field types for buttons, links, and enhanced title fields with additional display options.

## Functionality
- **Button Link Field**: Custom field type for creating styled button links
- **Enhanced Title Field**: Advanced title field with additional formatting options
- **Custom Widgets**: Specialized form widgets for enhanced field editing
- **Custom Formatters**: Multiple display formatters for different presentation needs
- **Component Integration**: SDC (Single Directory Components) integration for title components
- **Field Categories**: Custom field type categorization for better organization

## Key Components
- `ButtonLinkItem`: Field type for button link functionality
- `TitleItem`: Enhanced title field type with additional options
- `ButtonLinkWidget`: Form widget for button link fields
- `TitleDefaultWidget`: Form widget for title fields
- `ButtonLinkFormatter`: Display formatter for button links
- `TitleDefaultFormatter`: Display formatter for title fields
- SDC component integration for title display

## Dependencies

### Core Dependencies
- **link**: Drupal core Link field module for link functionality
- **field**: Drupal core Field module for field type system

### Contrib Dependencies
- None (uses only core APIs)

## Files Structure
```
sillage_fields/
├── components/
│   └── title/
│       ├── README.md                      # Title component documentation
│       ├── title.component.yml           # Component definition
│       └── title.twig                     # Title component template
├── css/
│   ├── button-link-widget.css            # Button link widget styles
│   └── title-field-widget.css            # Title field widget styles
├── src/
│   └── Plugin/
│       └── Field/
│           ├── FieldFormatter/
│           │   ├── ButtonLinkFormatter.php    # Button link formatter
│           │   └── TitleDefaultFormatter.php  # Title formatter
│           ├── FieldType/
│           │   ├── ButtonLinkItem.php         # Button link field type
│           │   └── TitleItem.php              # Title field type
│           └── FieldWidget/
│               ├── ButtonLinkWidget.php       # Button link widget
│               └── TitleDefaultWidget.php     # Title field widget
└── sillage_fields.field_type_categories.yml  # Field categorization
```

## Usage
These field types are available in the field management interface and can be added to any fieldable entity. They are specifically optimized for use with the Sillage theme framework but can be used with any theme.