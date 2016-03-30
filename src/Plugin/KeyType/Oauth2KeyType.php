<?php

/**
 * @file
 * Contains \Drupal\oauth2_key_client\Plugin\KeyType\AuthenticationKeyType.
 */

namespace Drupal\oauth2_key\Plugin\KeyType;

use Drupal\Core\Form\FormStateInterface;
use Drupal\key\Plugin\KeyTypeBase;



/**
 * Defines a generic key type for oauth2.
 *
 * @KeyType(
 *   id = "oauth2",
 *   label = @Translation("oauth2"),
 *   description = @Translation("A generic key type to use for a oauth 2 consumer key and secret with refresh and access tokens."),
 *   group = "oauth2",
 *   key_value = {
 *     "plugin" = "oauth2"
 *   }
 * )
 */
class Oauth2KeyType extends KeyTypeBase {
  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateKeyValue(array $configuration) {
    // Generate a random 16-character password.
    return user_password(16);
  }

  /**
   * {@inheritdoc}
   */
  public function validateKeyValue(array $form, FormStateInterface $form_state, $key_value) {
    // Validation of the key value is optional.

    kint($key_value);
  }

}
