<?php

namespace Drupal\nfl_events_plugin\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class NflEventsPluginConfigurationForm.
 */
class NflEventsPluginConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['nfl_events_plugin.api_key'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nfl_events_plugin_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('nfl_events_plugin.api_key');

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#description' => $this->t('This is the API key used for the scoreboard and team rankings API calls.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#default_value' => $config->get('api_key'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $api_key = $form_state->getValue('api_key');
    // Validate against non-alphanumeric characters.
    if (!ctype_alnum($api_key)) {
      $form_state->setErrorByName('api_key', $this->t('API Keys are always alphanumeric. Please correct the field.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('nfl_events_plugin.api_key')
      ->set('api_key', $form_state->getValue('api_key'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
