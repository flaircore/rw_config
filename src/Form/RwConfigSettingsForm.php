<?php
// RwConfigSettingsForm.php start ------------------------------------------------

namespace Drupal\rw_config\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class RwConfigSettingsForm
 *
 * @package Drupal\rw_config\Form
 *
 * see https://www.drupal.org/docs/drupal-apis/configuration-api/working-with-configuration-forms
 */
class RwConfigSettingsForm extends ConfigFormBase {

  protected function getEditableConfigNames() {
    return ['rw_config.settings'];
  }

  public function getFormId() {
    return 'rw_config_app_settings';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('rw_config.settings');

    $form['api_key'] = [
      '#default_value' => $config->get('api_key'),
      '#description' => $this->t('Your api key give at https://thecatapi.com/'),
      '#maxlength' => 40,
      '#required' => TRUE,
      '#title' => $this->t('API key'),
      '#type' => 'textfield',
    ];

    $form['app_title'] = [
      '#default_value' => $config->get('app_title'),
      '#description' => $this->t('Example page title'),
      '#maxlength' => 40,
      '#required' => TRUE,
      '#title' => $this->t('App title'),
      '#type' => 'textfield',
    ];

    $form['show_pictures'] = [
      '#default_value' => $config->get('show_pictures'),
      '#description' => $this->t('Show pictures '),
      '#title' => $this->t('Control if picture display on test page : ..../rwconfig/tests/page'),
      '#type' => 'checkbox',
    ];


    return parent::buildForm($form, $form_state);
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {

    # save to config and clear cache
    $config = $this->config('rw_config.settings');
    $config
      ->set('api_key', $form_state->getValue('api_key'))
      ->set('app_title', $form_state->getValue('app_title'))
      ->set('show_pictures', $form_state->getValue('show_pictures'))
      ->save();

    // clear cache
    drupal_flush_all_caches();

    parent::submitForm($form, $form_state);
  }

}


// RwConfigSettingsForm.php End ------------------------------------------------
