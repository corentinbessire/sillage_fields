<?php

namespace Drupal\lug_fields\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'title_default' widget.
 *
 * @FieldWidget(
 *   id = "title_default",
 *   label = @Translation("Title field"),
 *   field_types = {
 *     "title"
 *   }
 * )
 */
class TitleDefaultWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Get field settings to determine which options are available.
    $field_settings = $this->getFieldSettings();
    $allowed_sizes = array_filter($field_settings['allowed_sizes']);
    $allowed_styles = array_filter($field_settings['allowed_styles']);

    // Default values.
    $default_size = !empty($items[$delta]->size) ? $items[$delta]->size : $field_settings['default_size'];
    $default_style = !empty($items[$delta]->style) ? $items[$delta]->style : $field_settings['default_style'];

    // Create a fieldset container for better organization.
    $element += [
      '#type' => 'fieldset',
      '#title' => $this->t('Title'),
      '#attributes' => [
        'class' => ['title-field-widget-container'],
      ],
    ];

    // Title text field.
    $element['value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title text'),
      '#default_value' => $items[$delta]->value ?? NULL,
      '#size' => 60,
      '#maxlength' => 255,
      '#placeholder' => $this->t('Enter a title'),
      '#required' => $element['#required'],
    ];

    // Create a container for the formatting options.
    $element['formatting'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['title-field-formatting-options'],
      ],
    ];

    // Size dropdown (if any sizes are enabled)
    if (!empty($allowed_sizes)) {
      $size_options = array_intersect_key([
        'h1' => $this->t('H1 - Largest'),
        'h2' => $this->t('H2 - Large'),
        'h3' => $this->t('H3 - Medium'),
        'h4' => $this->t('H4 - Small'),
        'h5' => $this->t('H5 - Smaller'),
        'h6' => $this->t('H6 - Smallest'),
      ], $allowed_sizes);

      $element['formatting']['size'] = [
        '#type' => 'select',
        '#title' => $this->t('Size'),
        '#options' => $size_options,
        '#default_value' => isset($size_options[$default_size]) ? $default_size : key($size_options),
        '#weight' => 10,
      ];
    }
    else {
      // If no sizes are enabled, use the default size.
      $element['size'] = [
        '#type' => 'hidden',
        '#value' => $default_size,
      ];
    }

    // Style dropdown (if any styles are enabled)
    if (!empty($allowed_styles)) {
      $style_options = array_intersect_key([
        'primary' => $this->t('Primary'),
        'secondary' => $this->t('Secondary'),
        'accent' => $this->t('Accent'),
      ], $allowed_styles);

      $element['formatting']['style'] = [
        '#type' => 'select',
        '#title' => $this->t('Style'),
        '#options' => $style_options,
        '#default_value' => isset($style_options[$default_style]) ? $default_style : key($style_options),
        '#weight' => 20,
      ];
    }
    else {
      // If no styles are enabled, use the default style.
      $element['style'] = [
        '#type' => 'hidden',
        '#value' => $default_style,
      ];
    }

    // Add a preview message.
    $element['formatting']['preview'] = [
      '#type' => 'markup',
      '#markup' => '<div class="title-field-preview-message">' . $this->t('The title will be displayed according to the selected size and style.') . '</div>',
      '#weight' => 30,
    ];

    // Add custom CSS for the widget.
    $element['#attached']['library'][] = 'lug_fields/title_link_widget';

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    $values = parent::massageFormValues($values, $form, $form_state);

    foreach ($values as $delta => $value) {
      // Handle style and size values from nested 'formatting' container.
      if (isset($value['formatting']['style'])) {
        $values[$delta]['style'] = $value['formatting']['style'];
        unset($values[$delta]['formatting']['style']);
      }
      if (isset($value['formatting']['size'])) {
        $values[$delta]['size'] = $value['formatting']['size'];
        unset($values[$delta]['formatting']['size']);
      }

      // Remove the formatting container.
      if (isset($values[$delta]['formatting'])) {
        unset($values[$delta]['formatting']);
      }
    }

    return $values;
  }

}
