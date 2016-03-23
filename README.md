# Oauth2 Key




## type and key input plugins (

for storing oauth2 access and refresh tokens
(NOTE: Input may include a button to refresh tokens once there are existing tokens.
for simplicities sake for this proof of concept we assume a single key type and input
for all oauth2 keys with 2 fields: access_token and refresh_token)

## Form (FetchOauth2KeyForm)

that allows fetching tokens using pluggable clients and storing token values
in new Key Entities (key_type=oauth2).
you exchange certain grant info **which wont be stored** for token values 
which can be stored using in new Key Entities (key_type=oauth2).
(NOTE: should we allow retrieving tokens for one time display,
without creating new key entity?)
You can use this form if you dont yet have an access token value to manually
enter into the access and refresh token form fields (key_input=oauth2).
The form can be used in two ways:

1. load the default form via the route defined in routing.yml where you will
    find a select element to choose the client plugin
    (Note: thanks to key module for demonstrating custom select elements!) 
2. calling with optional parameter $client_id (plugin id) which will either 
    omit the select element or show it disabled and preselected
    (NOTE: this is also available as dynamic route)
3. retrieve this form from somewhere

$client = '{client_plugin_id}';
$form = \Drupal::formBuilder()->getForm('Drupal\oauth2_key\Form\Oauth2FetchTokenForm', $client);

Sidenote: a) is the reason clients are plugins and not services - if we use only b) we would probably make the clients into services)

## oauth2 client plugins
- creating a new plugin will ideally be done using a console command
- a plugin wraps around a composer package e.g. https://packagist.org/packages/league/oauth2-github

plugin manager will be included, is still a seperate module now
Note: we are only using this to create A key entity with our tokens, ignoring the ability to get authorized requests in this implementation on purpose

Note we are also skipping over how we handle different available grant types at this point

