<?php

namespace Drupal\lug_fields\Plugin\Field\FieldWidget;

use Drupal\link\Plugin\Field\FieldWidget\LinkWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'button_link' widget.
 *
 * @FieldWidget(
 *   id = "button_link",
 *   label = @Translation("Button Link"),
 *   field_types = {
 *     "button_link"
 *   }
 * )
 */
class ButtonLinkWidget extends LinkWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    // Get field settings to determine which options are available.
    $field_settings = $this->getFieldSettings();
    $allowed_styles = array_filter($field_settings['allowed_styles']);
    $allowed_sizes = array_filter($field_settings['allowed_sizes']);

    // Default values.
    $default_style = !empty($items[$delta]->style) ? $items[$delta]->style : $field_settings['default_style'];
    $default_size = !empty($items[$delta]->size) ? $items[$delta]->size : $field_settings['default_size'];

    // Convert the element into a fieldset for better organization.
    $element += [
      '#type' => 'fieldset',
      '#title' => $this->t('Button Link'),
      '#attributes' => [
        'class' => ['button-link-widget-container'],
      ],
    ];

    // Move the URI element into the fieldset and adjust its label.
    $uri_element = $element['uri'];
    unset($element['uri']);
    $uri_element['#title'] = $this->t('URL');
    $element['uri'] = $uri_element;

    // Move the title element if it exists and adjust its label.
    if (isset($element['title'])) {
      $title_element = $element['title'];
      unset($element['title']);
      $title_element['#title'] = $this->t('Button text');
      $element['title'] = $title_element;
    }

    // Create a container for the formatting options.
    $element['formatting'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['button-link-formatting-options'],
      ],
      '#weight' => 10,
    ];

    // Style dropdown (if any styles are enabled)
    if (!empty($allowed_styles)) {
      $style_options = array_intersect_key([
        'link' => $this->t('Link (default)'),
        'primary' => $this->t('Primary'),
        'secondary' => $this->t('Secondary'),
        'accent' => $this->t('Accent'),
      ], $allowed_styles);

      $element['formatting']['style'] = [
        '#type' => 'select',
        '#title' => $this->t('Style'),
        '#options' => $style_options,
        '#default_value' => isset($style_options[$default_style]) ? $default_style : key($style_options),
        '#weight' => 10,
      ];
    }
    else {
      // If no styles are enabled, use the default style.
      $element['style'] = [
        '#type' => 'hidden',
        '#value' => $default_style,
      ];
    }

    // Size dropdown (if any sizes are enabled)
    if (!empty($allowed_sizes)) {
      $size_options = array_intersect_key([
        'small' => $this->t('Small'),
        'medium' => $this->t('Medium'),
        'big' => $this->t('Big'),
      ], $allowed_sizes);

      $element['formatting']['size'] = [
        '#type' => 'select',
        '#title' => $this->t('Size'),
        '#options' => $size_options,
        '#default_value' => isset($size_options[$default_size]) ? $default_size : key($size_options),
        '#weight' => 20,
      ];
    }
    else {
      // If no sizes are enabled, use the default size.
      $element['size'] = [
        '#type' => 'hidden',
        '#value' => $default_size,
      ];
    }

    // Add a preview message.
    $element['formatting']['preview'] = [
      '#type' => 'markup',
      '#markup' => '<div class="button-link-preview-message">' . $this->t('The button will be displayed according to the selected style and size.') . '</div>',
      '#weight' => 30,
    ];

    // Add custom CSS for the widget.
    $element['#attached']['library'][] = 'lug_fields/button_link_widget';

    // Ensure attributes are preserved for options (if they exist)
    if (isset($element['options'])) {
      $options_element = $element['options'];
      unset($element['options']);
      $element['options'] = $options_element;
    }

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
