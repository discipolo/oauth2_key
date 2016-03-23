<?php

/**
 * @file
 * Contains \Drupal\oauth2_key_client\Plugin\KeyInput\Oauth2KeyInput.
 */

namespace Drupal\oauth2_key_client\Plugin\KeyInput;

use Drupal\Core\Form\FormStateInterface;
use Drupal\key\Plugin\KeyInputBase;

/**
 * Defines a key input that provides a simple text field.
 *
 * @KeyInput(
 *   id = "oauth2",
 *   label = @Translation("Oauth2 key input"),
 *   description = @Translation("A oauth2 key input.")
 * )
 */
class Oauth2KeyInput extends KeyInputBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'key_value' => '',
      'base64_encoded' => FALSE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $key_value_data = $form_state->get('key_value');
    $form['consumer_key'] = array(
      '#type' => 'textfield',
      '#title' => t('Consumer key'),
      '#description' => t('The consumer key for authenticating through OAuth.'),
      '#default_value' => $this->getConsumerKey(),
      '#required' => TRUE,
    );

    $form['consumer_secret'] = array(
      '#type' => 'textfield',
      '#title' => t('Consumer secret'),
      '#description' => t('The consumer secret for authenticating through OAuth.'),
      '#default_value' => $this->getConsumerSecret(),
      '#required' => TRUE,
    );
    $form['key_value'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Key value'),
      '#required' => $form_state->getFormObject()->getEntity()->getKeyProvider()->getPluginDefinition()['key_value']['required'],
      '#default_value' => $key_value_data['current'],
      // Tell the browser not to autocomplete this field.
      '#attributes' => ['autocomplete' => 'off'],
    );
    $form['refresh_token'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('refresh_token'),
      '#required' => $form_state->getFormObject()->getEntity()->getKeyProvider()->getPluginDefinition()['refresh_token']['required'],
      '#default_value' => $key_value_data['current'],
      // Tell the browser not to autocomplete this field.
      '#attributes' => ['autocomplete' => 'off'],
    );

    // If this key input is for an encryption key.
    if ($form_state->getFormObject()->getEntity()->getKeyType()->getPluginDefinition()['group'] == 'encryption') {
      // Add an option to indicate that the value is Base64-encoded.
      $form['base64_encoded'] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Base64-encoded'),
        '#description' => $this->t('Check this if the key value being submitted has been Base64-encoded.'),
        '#default_value' => $this->getConfiguration()['base64_encoded'],
      );
    }

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function getConsumerKey() {
    return $this->consumer_key;
  }

  /**
   * {@inheritdoc}
   */
  public function getConsumerSecret() {
    return $this->consumer_secret;
  }

}
