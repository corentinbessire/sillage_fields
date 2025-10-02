<?php

namespace Drupal\sillage_fields\Plugin\Field\FieldType;

use Drupal\link\Plugin\Field\FieldType\LinkItem;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'button_link' field type.
 *
 * @FieldType(
 *   id = "button_link",
 *   label = @Translation("Button Link"),
 *   description = @Translation("Stores a URL string with button style and size options."),
 *   category = "general",
 *   default_widget = "button_link",
 *   default_formatter = "button_link"
 * )
 */
class ButtonLinkItem extends LinkItem {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = parent::propertyDefinitions($field_definition);

    $properties['style'] = DataDefinition::create('string')
      ->setLabel(t('Button style'));

    $properties['size'] = DataDefinition::create('string')
      ->setLabel(t('Button size'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = parent::schema($field_definition);

    $schema['columns']['style'] = [
      'description' => 'The button style',
      'type' => 'varchar',
      'length' => 20,
      'not null' => FALSE,
    ];

    $schema['columns']['size'] = [
      'description' => 'The button size',
      'type' => 'varchar',
      'length' => 10,
      'not null' => FALSE,
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
      'allowed_styles' => [
        'link' => 'Link (default)',
        'primary' => 'Primary',
        'secondary' => 'Secondary',
        'accent' => 'Accent',
      ],
      'allowed_sizes' => [
        'small' => 'Small',
        'medium' => 'Medium',
        'big' => 'Big',
      ],
      'default_style' => 'link',
      'default_size' => 'medium',
    ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::fieldSettingsForm($form, $form_state);
    $settings = $this->getSettings();

    // Style options.
    $element['allowed_styles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Allowed styles'),
      '#description' => $this->t('Select which styles will be available in the widget.'),
      '#options' => [
        'link' => $this->t('Link (default)'),
        'primary' => $this->t('Primary'),
        'secondary' => $this->t('Secondary'),
        'accent' => $this->t('Accent'),
      ],
      '#default_value' => $settings['allowed_styles'],
      '#required' => TRUE,
    ];

    $element['default_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Default style'),
      '#description' => $this->t('Select the default style that will be used when creating new content.'),
      '#options' => [
        'link' => $this->t('Link (default)'),
        'primary' => $this->t('Primary'),
        'secondary' => $this->t('Secondary'),
        'accent' => $this->t('Accent'),
      ],
      '#default_value' => $settings['default_style'],
      '#required' => TRUE,
    ];

    // Size options.
    $element['allowed_sizes'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Allowed sizes'),
      '#description' => $this->t('Select which sizes will be available in the widget.'),
      '#options' => [
        'small' => $this->t('Small'),
        'medium' => $this->t('Medium'),
        'big' => $this->t('Big'),
      ],
      '#default_value' => $settings['allowed_sizes'],
      '#required' => TRUE,
    ];

    $element['default_size'] = [
      '#type' => 'select',
      '#title' => $this->t('Default size'),
      '#description' => $this->t('Select the default size that will be used when creating new content.'),
      '#options' => [
        'small' => $this->t('Small'),
        'medium' => $this->t('Medium'),
        'big' => $this->t('Big'),
      ],
      '#default_value' => $settings['default_size'],
      '#required' => TRUE,
    ];

    return $element;
  }

}
