<?php

/**
* Obtenez un jeton OAuth2 auprès d'un fournisseur OAuth2.
* * Installez ce script sur votre serveur afin qu'il soit accessible
* comme [https/http]://<votredomaine>/<dossier>/get_oauth_token.php
* par exemple : http://localhost/phpmailer/get_oauth_token.php
* * Assurez-vous que les dépendances sont installées avec 'composer install'
* * Configurer une application dans votre compte Google/Yahoo/Microsoft
* * Définissez l'adresse du script comme URL de redirection de l'application
* Si aucun jeton d'actualisation n'est obtenu lors de l'exécution de ce fichier,
* révoquez l'accès à votre application et réexécutez le script.
*/

espace de noms  PHPMailer \ PHPMailer ;

/**
* Alias ​​pour les classes de fournisseur de ligue
* Assurez-vous de les avoir ajoutés à votre composer.json et exécutez `composer install`
* Beaucoup de choix ici :
* @voir http://oauth2-client.thephpleague.com/providers/thirdparty/
*/
//@voir https://github.com/thephpleague/oauth2-google
utilisez  League \ OAuth2 \ Client \ Fournisseur \ Google ;
//@voir https://packagist.org/packages/hayageek/oauth2-yahoo
utilisez  Hayageek \ OAuth2 \ Client \ Fournisseur \ Yahoo ;
//@voir https://github.com/stevenmaguire/oauth2-microsoft
utilisez  Stevenmaguire \ OAuth2 \ Client \ Fournisseur \ Microsoft ;

if (! isset ( $ _GET [ 'code' ]) && ! isset ( $ _GET [ 'fournisseur' ])) {
    ?>
< html >
< body > Sélectionnez le fournisseur : < br >
< a  href =' ?provider=Google ' > Google </ a > < br >
< a  href =' ?provider=Yahoo ' > Yahoo </ a > < br >
< a  href =' ?provider=Microsoft ' > Microsoft/Outlook/Hotmail/Live/Office365 </ a > < br >
</ corps >
</ html >
    <?php
    sortie;
}

nécessite  'vendor/autoload.php' ;

session_start ();

$ nomdufournisseur = '' ;

if ( array_key_exists ( 'fournisseur' , $ _GET )) {
    $ nom_fournisseur = $ _GET [ 'fournisseur' ] ;
    $ _SESSION [ 'fournisseur' ] = $ nom_fournisseur ;
} elseif ( array_key_exists ( 'fournisseur' , $ _SESSION )) {
    $ nom_fournisseur = $ _SESSION [ 'fournisseur' ] ;
}
if (! in_array ( $ nom_fournisseur , [ 'Google' , 'Microsoft' , 'Yahoo' ])) {
    exit ( 'Seuls les fournisseurs Google, Microsoft et Yahoo OAuth2 sont actuellement pris en charge dans ce script.' );
}

//Ces détails sont obtenus en configurant une application dans la console développeur de Google,
//ou quel que soit le fournisseur que vous utilisez.
$ clientId = 'RANDOMCHARS-----duv1n2.apps.googleusercontent.com' ;
$ clientSecret = 'RANDOMCHARS-----lGyjPcRtvP' ;

//Si cette URL automatique ne fonctionne pas, définissez-la vous-même manuellement sur l'URL de ce script
$ redirectUri = ( isset ( $ _SERVER [ 'HTTPS' ]) ? 'https://' : 'http://' ) . $ _SERVEUR [ 'HTTP_HOST' ] . $ _SERVEUR [ 'PHP_SELF' ] ;
//$redirectUri = 'http://localhost/PHPMailer/redirect';

$ paramètres = [
    'clientId' => $ clientId ,
    'clientSecret' => $ clientSecret ,
    'redirectUri' => $ redirectUri ,
    'accessType' => 'hors ligne'
] ;

$ options = [] ;
$ fournisseur = null ;

switch ( $ nom_fournisseur ) {
    cas  'Google' :
        $ fournisseur = nouveau  Google ( $ params );
        $ options = [
            'portée' => [
                'https://mail.google.com/'
            ]
        ] ;
        casser ;
    cas  'Yahoo' :
        $ fournisseur = nouveau  Yahoo ( $ params );
        casser ;
    cas  'Microsoft' :
        $ fournisseur = nouveau  Microsoft ( $ params );
        $ options = [
            'portée' => [
                'wl.imap' ,
                'wl.offline_access'
            ]
        ] ;
        casser ;
}

if ( null === $ fournisseur ) {
    exit ( 'Fournisseur manquant' );
}

si (! isset ( $ _GET [ 'code' ])) {
    // Si nous n'avons pas de code d'autorisation, obtenez-en un
    $ authUrl = $ fournisseur -> getAuthorizationUrl ( $ options );
    $ _SESSION [ 'oauth2state' ] = $ fournisseur -> getState ();
    en-tête ( 'Emplacement : ' . $ authUrl );
    sortie;
    // Vérifier l'état donné par rapport à celui précédemment stocké pour atténuer l'attaque CSRF
} elseif ( vide ( $ _GET [ 'état' ]) || ( $ _GET [ 'état' ] !== $ _SESSION [ 'oauth2state' ])) {
    unset( $ _SESSION [ 'oauth2state' ]);
    unset( $ _SESSION [ 'fournisseur' ]);
    exit ( 'Etat invalide' );
} sinon {
    unset( $ _SESSION [ 'fournisseur' ]);
    //Essayez d'obtenir un jeton d'accès (en utilisant le code d'autorisation)
    $ jeton = $ fournisseur -> getAccessToken (
        'code_autorisation' ,
        [
            'code' => $ _GET [ 'code' ]
        ]
    );
    // Utilisez ceci pour interagir avec une API au nom des utilisateurs
    // Utilisez ceci pour obtenir un nouveau jeton d'accès si l'ancien expire
    echo  'Refresh Token: ' , $ token -> getRefreshToken ();
}