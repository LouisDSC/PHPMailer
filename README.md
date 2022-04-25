![PHPMailer](https://github.com/LouisDSC/PHPMailer/blob/master/img_PHP_Mailer.png)

# PHPMailer | Création et transfert d'e-mail pour vos formulaire PHP
## Caractéristiques
- Probablement le code le plus populaire au monde pour envoyer des e-mails depuis PHP !
- Utilisé par de nombreux projets open source : WordPress, Drupal, 1CRM, SugarCRM, Yii, Joomla ! et beaucoup plus
- Prise en charge SMTP intégrée - envoi sans serveur de messagerie local
- Envoyez des e-mails avec plusieurs adresses À, CC, CCI et Répondre à
- E-mails en plusieurs parties/alternatifs pour les clients de messagerie qui ne lisent pas les e-mails HTML
- Ajouter des pièces jointes, y compris en ligne
- Prise en charge du contenu UTF-8 et des encodages 8 bits, base64, binaires et imprimables entre guillemets
- Authentification SMTP avec les mécanismes LOGIN, PLAIN, CRAM-MD5 et XOAUTH2 sur les transports SMTPS et SMTP+STARTTLS
- Valide automatiquement les adresses e-mail
- Protège contre les attaques par injection d'en-tête
- Messages d'erreur dans plus de 50 langues !
- Prise en charge de la signature DKIM et S/MIME
- Compatible avec PHP 5.5 et versions ultérieures, y compris PHP 8.1
- Espace de noms pour éviter les conflits de noms

## Contexte `mail()`
La fonction PHP `mail()` envoie généralement via un serveur de messagerie local, généralement dirigé par un `sendmail()` binaire sur les plates-formes Linux, BSD et macOS, cependant, Windows n'inclut généralement pas de serveur de messagerie local ; Le client SMTP intégré de PHPMailer permet l'envoi d'e-mails sur toutes les plates-formes sans avoir besoin d'un serveur de messagerie local. Sachez cependant que la mail()fonction doit être évitée dans la mesure du possible ; il est à la fois plus rapide et plus sûr d'utiliser SMTP vers localhost.

Si vous n'utilisez pas PHPMailer, il existe de nombreuses autres excellentes bibliothèques que vous devriez consulter avant de lancer la vôtre. Essayez[SwiftMailer](https://swiftmailer.symfony.com/)
, [Laminas/Mail](https://docs.laminas.dev/laminas-mail/), [ZetaComponents](https://github.com/zetacomponents/Mail) etc.

## Installation
PHPMailer est disponible sur [Packagist](https://packagist.org/packages/phpmailer/phpmailer). 
La méthode recommandée pour installer PHPMailer est d'ajouter ce bout de code dans votre fichier `composer.json` :

```json
"phpmailer/phpmailer": "^6.5"
```

ou si vous utilisez NPM & NodeJs :

```npm
composer require phpmailer/phpmailer
```

Dans le dossier `vendor`, le fichier script `vendor/autoload.php` est automatiquement généré par <i>Composer</i> et ne fait pas partir PHPMailer.

If you want to use the Gmail XOAUTH2 authentication class, you will also need to add a dependency on the `league/oauth2-client` package in your `composer.json`.

Alternatively, if you're not using Composer, you
can [download PHPMailer as a zip file](https://github.com/PHPMailer/PHPMailer/archive/master.zip), (note that docs and examples are not included in the zip file), then copy the contents of the PHPMailer folder into one of the `include_path` directories specified in your PHP configuration and load each class file manually:

```php
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';
```

Même si vous n'utilisez pas la classe `SMTP`, vous devez toujours charger la classe `Exception` car elle est utilisée en interne.
### Installation importante !
Bien que l'installation de l'ensemble du package manuellement ou avec Composer soit simple, pratique et fiable, vous souhaiterez peut-être n'inclure que les fichiers vitaux dans votre projet. Au minimum, vous aurez besoin de [src/PHPMailer.php](https://github.com/LouisDSC/PHPMailer/blob/master/src/PHPMailer.php). 
- Si vous utilisez SMTP, vous aurez besoin de [src/SMTP.php](https://github.com/LouisDSC/PHPMailer/blob/master/src/SMTP.php).
 -Si vous utilisez POP avant SMTP ( très peu probable !), vous aurez besoin de[src/POP3.php](https://github.com/LouisDSC/PHPMailer/blob/master/src/POP3.php).
 - Si vous utilisez XOAUTH2, vous aurez besoin de [src/OAuth.php](https://github.com/LouisDSC/PHPMailer/blob/master/src/OAuth.php) ainsi que des dépendances Composer pour les services avec lesquels vous souhaitez vous authentifier. Vraiment, c'est beaucoup plus simple d'utiliser Composer ! Je vous le conseil vivement !

## Exemple que vous pouvez utiliser

```php
<?php
//Importer les classes PHPMailer dans l'espace de noms global 
//Celles-ci doivent être en haut de votre script, pas à l'intérieur d'une fonction 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Le chargeur automatique de Load Composer 
require 'vendor/autoload.php';

//Créer une instance ; passer `true` active les exceptions 
$mail = new PHPMailer(true);

try {
    //Paramètres du serveur
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Activer la sortie de débogage détaillée
    $mail->isSMTP();                                            // Envoi via SMTP
    $mail->Host       = 'smtp.example.com';                     //Définir le serveur SMTP pour envoyer via
    $mail->SMTPAuth   = true;                                   //Activer l'authentification SMTP 
    $mail->Username   = 'user@example.com';                     //nom d'utilisateur SMTP 
    $mail->Password   = 'secret';                               // Mot de passe SMTP
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Activer le chiffrement TLS implicite 
    $mail->Port       = 465;                                    //Port TCP auquel se connecter ; utilisez 587 si vous avez défini `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Destinataires
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('louis@example.net', 'Louis Japheth Kouassi');     //Ajouter un destinataire
    $mail->addAddress('japheth@example.com');               //Le nom est facultatif
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

     //Pièces jointes 
    $mail->addAttachment('/var/tmp/file.tar.gz');         //Ajouter des pièces jointes
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Le nom est facultatif

    //Contenu
    $mail->isHTML(true);                                  // Définissez le format de l'e-mail sur HTML
    $mail->Subject = 'Sujet';
    $mail->Body    = 'Ceci est le corps du message HTML <b>en gras !</b>';
    $mail->AltBody = 'Ceci est le corps en texte brut pour les clients de messagerie non-HTML';

    $mail->send();
    echo 'Votre message a bien été envoyé';
} catch (Exception $e) {
    echo "Votre message n'a pas pu être envoyé. Erreur Mailer : {$mail->ErrorInfo}";
}
```

## Localization
PHPMailer est par défaut en anglais, mais dans le dossier de langue , vous trouverez de nombreuses traductions pour les messages d'erreur PHPMailer que vous pourriez rencontrer. Pour spécifier une langue, vous devez indiquer à PHPMailer laquelle utiliser, comme ceci :

```php
//Pour charger la version français
$mail->setLanguage('fr', '/optional/path/to/language/directory/');
```

