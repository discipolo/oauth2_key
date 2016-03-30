<?php

/**
 * @file
 * Contains \Drupal\oauth2_key_client\Plugin\KeyInput\Oauth2KeyInput.
 */

namespace Drupal\oauth2_key\Plugin\KeyInput;

use Drupal\Core\Form\FormStateInterface;
use Drupal\key\Plugin\KeyInputBase;

// if we add the oauth2 client directly to the input
// for guzzle-oauth2-plugin token retrieval helper
// but this doesnt feel like its in the scope of key input

//use GuzzleHttp\Client;
//use CommerceGuys\Guzzle\Oauth2\GrantType\RefreshToken;
//use CommerceGuys\Guzzle\Oauth2\GrantType\PasswordCredentials;
//use CommerceGuys\Guzzle\Oauth2\GrantType\ClientCredentials;
//use CommerceGuys\Guzzle\Oauth2\Middleware\OAuthMiddleware;
//use GuzzleHttp\HandlerStack;

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
      'consumer_key' => '',
      'base64_encoded' => FALSE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $key_value_data = $form_state->get('key_value');
    // NOTE: we dont want to necessarily store grant information as part of the key, so it doesnt seem right to put them into the input directly

//    $form['consumer_key'] = array(
//      '#type' => 'textfield',
//      '#title' => t('Consumer key'),
//      '#description' => t('The consumer key for authenticating through OAuth.'),
//      '#default_value' => $this->getConsumerKey(),
//      '#required' => TRUE,
//    );
//
//    $form['consumer_secret'] = array(
//      '#type' => 'textfield',
//      '#title' => t('Consumer secret'),
//      '#description' => t('The consumer secret for authenticating through OAuth.'),
//      '#default_value' => $this->getConsumerSecret(),
//      '#required' => TRUE,
//    );

//    $form['key_value']['refresh_token'] = array(
//      '#type' => 'textfield',
//      '#title' => $this->t('refresh_token'),
//      '#required' => $form_state->getFormObject()->getEntity()->getKeyProvider()->getPluginDefinition()['key_value']['required'],
//      '#default_value' => $key_value_data['current'],
//      // Tell the browser not to autocomplete this field.
//      '#attributes' => ['autocomplete' => 'off'],
    //   );
    // TODO: figure out if we need to create a new provider if we want to store multiple values in one key. for now we will treat the access token as the key value
    $form['key_value'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('access_token'),
      '#required' => $form_state->getFormObject()->getEntity()->getKeyProvider()->getPluginDefinition()['key_value']['required'],
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


// NOTE: fetching in process submitted key function works but doesnt seem right either. thanks to https://www.drupal.org/node/2693145 this is now easier
  /**
   * {@inheritdoc}
   */
//  public function processSubmittedKeyValue(FormStateInterface $form_state) {
//
//    $processed_values = array(
//      'submitted' => NULL,
//      'processed_submitted' => NULL,
//    );
//    $key_input_settings = $form_state->getValues();
//    $key_value_data = $form_state->get('key_value');
//    $key_helper_data = $form_state->get('helper');
//
//    if (isset($key_input_settings['create_key'])) {
//
//      $consumer_secret = $key_input_settings['helper']['consumer_secret'];
//      $consumer_id = $key_input_settings['helper']['consumer_id'];
//      $token_endpoint = $key_input_settings['helper']['token_endpoint'];
//      $baseurl = $key_input_settings['helper']['baseurl'];
//
//      $config = [
//        // ClientCredentials::USERNAME => 'test@example.com',
//        ClientCredentials::CONFIG_CLIENT_ID => $consumer_id,
//        ClientCredentials::CONFIG_CLIENT_SECRET => $consumer_secret,
//        ClientCredentials::CONFIG_TOKEN_URL => $token_endpoint,
//        'scope' => 'administration',
//      ];
//
//      $access_token = $this->getTokens($baseurl, $config);
//      $key_input_settings['key_value'] = $access_token;
//    }
//
//    // Deal with key value is filled
//    if (isset($key_input_settings['key_value'])) {

//      // If the submitted key value is equal to the obscured value.
//      if ($key_input_settings['key_value'] == $key_value_data['obscured']) {
//        // Use the processed original value as the submitted value.
//        $processed_values['submitted'] = $key_value_data['processed_original'];
//      }
//      else {
//        $processed_values['submitted'] = $key_input_settings['key_value'];
//      }
//
//      if (isset($key_input_settings['base64_encoded']) && $key_input_settings['base64_encoded'] == TRUE) {
//        $processed_values['processed_submitted'] = base64_decode($processed_values['submitted']);
//      }
//      else {
//        $processed_values['processed_submitted'] = $processed_values['submitted'];
//      }
//
//      unset($key_input_settings['key_value']);
//      $form_state->setValues($key_input_settings);
//    }
//
//    return $processed_values;
//  }
//  /**
//   * This will be called by key processor and use guzzle2-oauth2-plugin library to get key values (tokens)
//   */
//  public function getTokens($baseurl = 'https://example.com', $config) {
//
//
//
//    $handlerStack = HandlerStack::create();
//    $client = new Client(['handler'=> $handlerStack, 'base_uri' => $baseurl, 'auth' => 'oauth2']);
//
//
//
//    $grant = new ClientCredentials($client, $config);
//
////    $grant = new PasswordCredentials($client, $config);
//    $refreshToken = new RefreshToken($client, $config);
//    $middleware = new OAuthMiddleware($client, $grant, $refreshToken);
////
//    $handlerStack->push($middleware->onBefore());
//    $handlerStack->push($middleware->onFailure(5));
//
//    $access_token = $middleware->getAccessToken();
//    $refresh_token = $middleware->getRefreshToken();
//    $tokens = array(
//      'access_token' => $access_token,
//      'refresh_token' => $refresh_token
//    );
//
////    $response = $client->get('/api/user/me');
////
////    print_r($response->json());
//
//// Use $middleware->getAccessToken(); and $middleware->getRefreshToken() to get tokens
//// that can be persisted for subsequent requests.
//
//    //return $this->consumer_secret;
//
//    return $tokens;
//
//  }

}
