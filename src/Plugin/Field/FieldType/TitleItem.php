<?php

namespace Drupal\sillage_fields\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'title' field type.
 *
 * @FieldType(
 *   id = "title",
 *   label = @Translation("Title"),
 *   description = @Translation("This field stores title text."),
 *   category = "general",
 *   default_widget = "title_default",
 *   default_formatter = "title_default"
 * )
 */
class TitleItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Title text'));
    $properties['size'] = DataDefinition::create('string')
      ->setLabel(t('Title size'));
    $properties['style'] = DataDefinition::create('string')
      ->setLabel(t('Title style'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
        ],
        'size' => [
          'type' => 'varchar',
          'length' => 10,
          'not null' => FALSE,
        ],
        'style' => [
          'type' => 'varchar',
          'length' => 20,
          'not null' => FALSE,
        ],
      ],
      'indexes' => [
        'value' => ['value'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
      'allowed_sizes' => [
        'h1' => 'h1',
        'h2' => 'h2',
        'h3' => 'h3',
        'h4' => 'h4',
        'h5' => 'h5',
        'h6' => 'h6',
      ],
      'allowed_styles' => [
        'primary' => 'Primary',
        'secondary' => 'Secondary',
        'accent' => 'Accent',
      ],
      'default_size' => 'h2',
      'default_style' => 'primary',
    ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function mainPropertyName() {
    return 'value';
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::fieldSettingsForm($form, $form_state);
    $settings = $this->getSettings();

    // Size options.
    $element['allowed_sizes'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Allowed HTML sizes'),
      '#description' => $this->t('Select which HTML heading sizes will be available in the widget.'),
      '#options' => [
        'h1' => $this->t('H1 - Largest'),
        'h2' => $this->t('H2 - Large'),
        'h3' => $this->t('H3 - Medium'),
        'h4' => $this->t('H4 - Small'),
        'h5' => $this->t('H5 - Smaller'),
        'h6' => $this->t('H6 - Smallest'),
      ],
      '#default_value' => $settings['allowed_sizes'],
      '#required' => TRUE,
    ];

    $element['default_size'] = [
      '#type' => 'select',
      '#title' => $this->t('Default size'),
      '#description' => $this->t('Select the default size that will be used when creating new content.'),
      '#options' => [
        'h1' => $this->t('H1 - Largest'),
        'h2' => $this->t('H2 - Large'),
        'h3' => $this->t('H3 - Medium'),
        'h4' => $this->t('H4 - Small'),
        'h5' => $this->t('H5 - Smaller'),
        'h6' => $this->t('H6 - Smallest'),
      ],
      '#default_value' => $settings['default_size'],
      '#required' => TRUE,
    ];

    // Style options.
    $element['allowed_styles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Allowed styles'),
      '#description' => $this->t('Select which styles will be available in the widget.'),
      '#options' => [
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
        'primary' => $this->t('Primary'),
        'secondary' => $this->t('Secondary'),
        'accent' => $this->t('Accent'),
      ],
      '#default_value' => $settings['default_style'],
      '#required' => TRUE,
    ];

    return $element;
  }

}
