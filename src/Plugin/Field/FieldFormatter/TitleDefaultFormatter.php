<?php

namespace Drupal\lug_fields\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'title_default' formatter.
 *
 * @FieldFormatter(
 *   id = "title_default",
 *   label = @Translation("Default"),
 *   field_types = {
 *     "title"
 *   }
 * )
 */
class TitleDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $field_settings = $this->getFieldSettings();

    foreach ($items as $delta => $item) {
      // Use the specified size or fall back to default.
      $tag = !empty($item->size) ? $item->size : $field_settings['default_size'];

      // Create classes based on style.
      $style = !empty($item->style) ? $item->style : $field_settings['default_style'];

      $elements[$delta] = [
        '#type' => 'component',
        '#component' => 'lug_fields:title',
        '#props' => [
          'text' => $item->value,
          'style' => $style,
          'size' => $tag,
          'attributes' => [
            'id' => 'my-title',
          ],
        ],
      ];

    }

    return $elements;
  }

}
