# REST API BREVO PHP

## Requêtes

Les demandes doivent être envoyées par HTTPS et toute charge utile doit être formatée en JSON (application/json). Chaque demande doit inclure les en-têtes `content-type : application/json` et `api-key`.

**_<ins>Exemple :</ins>_**
```php
<?php
// PHP SDK: https://github.com/sendinblue/APIv3-php-library
require_once(__DIR__ . '/vendor/autoload.php');

// Configure API key authorization: api-key
$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', 'YOUR_API_KEY');

$apiInstance = new SendinBlue\Client\Api\ContactsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$createContact = new \SendinBlue\Client\Model\CreateContact(); // \SendinBlue\Client\Model\CreateContact | Values to create a contact
$createContact['email'] = 'john@doe.com';
  
try {
    $result = $apiInstance->createContact($createContact);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ContactsApi->createContact: ', $e->getMessage(), PHP_EOL;
}
?>
```
## Pagination
En fonction du point d'accès et de votre demande, les résultats renvoyés peuvent être paginés. Vous pouvez paginer les résultats en utilisant les paramètres suivants dans la chaîne de requête.

| Name | Type | Description |
|:-----|:-----|:------------|
| limit | integer | Le nombre de résultats renvoyés par page. La valeur par défaut et la valeur maximale peuvent varier selon l'API |
| offset | interger | L'index du premier document de la page (en commençant par 0). Par exemple, si la limite est de 50 et que vous voulez récupérer la page 2, alors offset=50 |

## Résponses
### Format
Les réponses peuvent être soit vide, soit un objet JSON.

En cas de succès, l'objet JSON renvoyé pour chaque point d'extrémité est différent.

Réponse en cas de succès :
```JSON
{
  "ips": [
    {
      "id": 3,
      "ip": "123.65.8.22",
      "domain": "mailing.myshop.dom"
    },
    {
      "id": 5,
      "ip": "123.43.21.3",
      "domain": "newsletter.myshop.dom"
    }
  ]
}
```

Un objet d'erreur contiendra un code d'erreur et une description lisible par l'homme de l'erreur.

Réponse en cas d'échec :
```JSON
{
  "code": "invalid_parameter",
  "message": "Invalid email address"
}
```
| HTTP code | Status | Description |
|:----------|:-------|:------------|
| 200 | OK | La requête est un succès |
| 201 | Created | L'objet a été créer avec succès |
| 202 | Accepted | La requête a été accepter et vas être traitée |
| 204 | No content | L'objet a été mis à jour ou supprimer avec succès |
| 400 | Bad request | Requête invalide. Regarde l'erreur dans le code dans le fichier JSON |
| 401 | Unauthorized | Vous n'avez pas été authentifier. Assurez-vous que la clé d'accès fournie est correcte |
| 402 | Payment Required | Assurez-vous que votre compte est activé et que vous disposez de suffisamment de crédits. |
| 403 | Forbidden | Vous n'avez pas le droit d'accéder a cette ressource |
| 404 | Not Found | Assurez-vous que vous appelez un point de terminaison existant et que les paramètres (identifiant de l'objet, etc.) dans le chemin sont corrects. |
| 405 | Method Not Allowed | Le verbe que vous utilisez n'est pas autorisé pour ce point d'accès. Vérifiez que vous utilisez la bonne méthode (GET, POST, PUT, DELETE). |
| 406 | Not Acceptable | La valeur de `contentType` pour les requêtes PUT ou POST dans les en-têtes de requête n'est pas `application/json`. Assurez-vous que la valeur est uniquement `application/json` et qu'elle n'est pas vide.|
| 429 | Too Many Requests | La limite du taux attendu est dépassée. Référer vous [ici](https://developers.sendinblue.com/docs/faq#section-what-is-the-limits-on-api-calls) |

### Codes d'erreur

Référence des codes d'erreur [ici](https://developers.brevo.com/docs/how-it-works#error-codes)


## Import de contacts

Référer vous [ici](https://developers.brevo.com/docs/synchronise-contact-lists)

**_<ins>Exemple :</ins>_**
```php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure API key authorization: api-key
$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', 'YOUR_API_KEY');

// Uncomment below line to configure authorization using: partner-key
// $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('partner-key', 'YOUR_API_KEY');

$apiInstance = new SendinBlue\Client\Api\ContactsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$createContact = new \SendinBlue\Client\Model\CreateContact(); // \SendinBlue\Client\Model\CreateContact | Values to create a contact

$createContact['email'] = 'testmail@example.com';
$createContact['attributes'] = array('SMS'=>'919900994', 'FNAME'=>'John', 'LNAME'=>'Doe');
$createContact['listIds'] = array(11);
$createContact['emailBlacklisted'] = false;
$createContact['smsBlacklisted'] = false;
$createContact['updateEnabled'] = false;

try {
    $result = $apiInstance->createContact($createContact);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ContactsApi->createContact: ', $e->getMessage(), PHP_EOL;
}
```

## Créer des contacts

Référer vous [ici](https://developers.brevo.com/reference/createcontact)

**_<ins>Exemple :</ins>_**
```php
  require_once('vendor/autoload.php');

  $client = new \GuzzleHttp\Client();

  $response = $client->request('POST', 'https://api.brevo.com/v3/contacts', [
    'body' => '{"email":"elly@example.com","ext_id":"externalId","attributes":{"FNAME":"Elly","LNAME":"Roger","COUNTRIES":["India","China"]},"emailBlacklisted":false,"smsBlacklisted":false,"listIds":[36],"updateEnabled":false,"smtpBlacklistSender":["user@example.com"]}',
    'headers' => [
      'accept' => 'application/json',
      'api-key' => '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',
      'content-type' => 'application/json',
    ],
  ]);

  echo $response->getBody();
```

## MAJ des contacts

Référence [ici](https://developers.brevo.com/docs/synchronise-contact-lists#update-your-contact)

Exemple :
```php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure API key authorization: api-key
$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', 'YOUR_API_KEY');

// Uncomment below line to configure authorization using: partner-key
// $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('partner-key', 'YOUR_API_KEY');

$apiInstance = new SendinBlue\Client\Api\ContactsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$email = "testmail@example.com"; // string | Email (urlencoded) of the contact
$updateContact = new \SendinBlue\Client\Model\UpdateContact(); // \SendinBlue\Client\Model\UpdateContact | Values to update a contact
$updateContact['attributes'] = array('FNAME'=>'Alex', 'LNAME'=>'Roger');

try {
    $apiInstance->updateContact($email, $updateContact);
} catch (Exception $e) {
    echo 'Exception when calling ContactsApi->updateContact: ', $e->getMessage(), PHP_EOL;
}
```

## Documentation pour l'importantion de contact grâce a un fichier .txt, .csv & .json
cf [ici](https://developers.brevo.com/reference/importcontacts-1)


## Documentation GitHub API Brevo
cf [ici](https://github.com/sendinblue/APIv3-php-library/blob/master/docs/Api/ContactsApi.md#createcontact)


# Site utile pour les requêtes a des API
cf [ici](https://julien-9932109.postman.co/workspace/97c8ff16-1ebb-4aba-82d4-63cad787decc/request/45160365-35258f79-de20-4be2-b771-6efb9ad25ae8?tab=headers)