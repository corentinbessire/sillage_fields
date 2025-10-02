<?php

namespace Drupal\sillage_fields\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'button_link' formatter.
 *
 * @FieldFormatter(
 *   id = "Button link",
 *   label = @Translation("Button Link"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class ButtonLinkFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'target' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);

    $elements['target'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Open link in new window'),
      '#default_value' => $this->getSetting('target'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();

    if ($this->getSetting('target')) {
      $summary[] = $this->t('Opens in new window');
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $field_settings = $this->getFieldSettings();

    foreach ($items as $delta => $item) {
      // Get default values if not set.
      $style = !empty($item->style) ? $item->style : $field_settings['default_style'];
      $size = !empty($item->size) ? $item->size : $field_settings['default_size'];

      $url = $item->getUrl() ?: Url::fromRoute('<none>');
      $url_string = $url->toString();

      // Create attributes for the button.
      $attributes = [];

      // Add target attribute if configured.
      if ($this->getSetting('target')) {
        $attributes['target'] = '_blank';
      }

      $elements[$delta] = [
        '#type' => 'component',
        '#component' => 'sillage_fields:button',
        '#props' => [
          'text' => $item->title ?? $url_string,
          'url' => $url,
          'style' => $style,
          'size' => $size,
          'attributes' => [
            'class' => [
              'button',
              'button--' . $style,
              'button--size-' . $size,
            ],
          ],
        ],
      ];

      if ($this->getSetting('target')) {
        $elements[$delta]['#attributes']['target'] = '_blank';
      }
    }

    return $elements;
  }

}
