<?php

/**
 * @file
 * Contains \Drupal\oauth2_key\Form\Oauth2FetchTokenForm.
 */

namespace Drupal\oauth2_key\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\oauth2_key_client\Oauth2KeyClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
// TODO: figure out what we need to create a new plugin instance
// plugin.manager.oauth2_key_client
// TODO: and key entity and how to inject it

/**
 * Class Oauth2FetchTokenForm.
 *
 * @package Drupal\oauth2_key\Form
 */
class Oauth2FetchTokenForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'oaut2_fetch_token_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $client = NULL) {
    kint($client);
    $form['oauth2_key_client'] = [
      '#type' => 'oauth2_key_client_select',
      '#multiple' => FALSE,
      '#default_value' => $client,
      '#header' => [
        'title' => $this->t('Definition'),
        'category' => $this->t('Category'),
        'description' => $this->t('Description'),
      ],
    ];
    // TODO: figure out the redirection error
    $form['info'] = [
      '#type' => 'details',
      '#description' => $this->t('For some reason you have to fill out and submit this twice currently to actually create a key'),
    ];

    $form['consumer_key'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Consumer Key'),
      '#description' => $this->t('enter your consumer key which wont be saved. make sure its the one you registered on your provider'),
      '#maxlength' => 64,
      '#size' => 64,
    );

    $form['consumer_secret'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Consumer Secret'),
      '#description' => $this->t('enter your consumer secret. make sure its the one you registered on your provider'),
      '#maxlength' => 64,
      '#size' => 64,
    );
    $form['redirect_url'] = array(
      '#type' => 'url',
      '#title' => $this->t('redirect_url'),
      '#description' => $this->t('use the complete path of the form you are currently on and make sure its the one you registered on your provider'),
      '#maxlength' => 64,
      '#size' => 64,
    );
    $form['baseurl'] = array(
      '#type' => 'url',
      '#title' => $this->t('baseurl'),
      '#description' => $this->t('could use this for situations where we dont know what rempote url to authenticate against...'),
      '#maxlength' => 64,
      '#size' => 64,
    );

    // Group submit handlers in an actions element with a key of "actions" so
    // that it gets styled correctly, and so that other modules may add actions
    // to the form. This is not required, but is convention.
    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $client_id = $form_state->getValue('oauth2_key_client');
    $settings = $form_state->getValues();
    // TODO: inject this if we even need it to instanciate the plugin (we are using plugin instances only once to store their output to key entities.)

    $client_manager = \Drupal::service('plugin.manager.oauth2_key_client');
    $client_plugin_definition = $client_manager->getDefinition($client_id);

    // TODO: what is the submit action if its not overridden per plugin? we want to create a client instance, fetch tokens and store as key entities
    $config = $settings;
    $client_plugin = $client_manager->createInstance($client_id, $config, $client_plugin_definition);

    // now we can use the client here
    $key_entity = $client_plugin->createKeyEntity();
    $key_entity_saved = $key_entity->save();

    drupal_set_message(t('You saved key %id.', ['%id' => $key_entity->id()]));
  }

}
