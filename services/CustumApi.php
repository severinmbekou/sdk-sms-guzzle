<?php
require("GuzzleHttpService.php"); // Necessité d'utiliser GuzzleHttp pour les requêtes externes.

/*
* SDK LEUTON GROUP
* Version V0.8
*/

class CustumAPI
{
    /*
    * Identification du développeur(client id).
    */
    private $appKey;
    /*
    * Clé secrète du développeur(client secret).
    */
    private $appSecret;

    // URL du serveur OAuth de 3ibusinessapi.
    private $BASE_URL = "https://proxy.recf.3ibusiness.com";

    private $guzzleHttpService;                // L'instance curl utilisé pour faire les requêtes post.

    private $accessToken;
    private $refreshToken;                // Refresh token à utiliser lors de l'expiration du token actuel.

    private $tokenDate;                    // Date d'expiration du token actuellement utiliser.


    // Objet singleton servant à faire les requêtes ves le serveur.
    public static $instance = NULL;

    /**
     * Object singleton permettant de conserver l'état de  l'utilisation
     * de l'API:
     * les coordonnées du dévelopeur, l'access token, le refresh token,
     * la date d'expirationd du token et les url à appeller.
     */
    public static function getInstance($appKey, $appSecret, $appId)
    {
        if (static::$instance === NULL) {
            static::$instance = new CustumAPI();

            static::$instance->appKey = $appKey;
            static::$instance->appSecret = $appSecret;
            static::$instance->accessToken = "0000000000";
            static::$instance->refreshToken = "0000000000";
            static::$instance->tokenDate = time();

            static::$instance->guzzleHttpService = new GuzzleHttpService(static::$instance->BASE_URL, $appId);
        }

        return static::$instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /*
    * <p>Authentification du developpeur à l'aide de ses credentials.</p>
    * Et Mise à jour automatique du access token lors de son expiration
    */
    public function oauthAuthenticate()
    {
        // Date courante pour tester si le token de sécurité est expiré.
        $currentDate = time();

        // Si le token a déjà été récupéré pour la première on le revoi s'il n'est pas expiré.
        if (static::$instance->tokenDate < $currentDate) {
            // On retourne le token puisqu'il n'est pas expiré.
            return static::$instance->accessToken;
        } else {
            // Request a new token
            static::$instance->guzzleHttpService->setOauthData(array("appKey" => static::$instance->appKey, "appSecret" => static::$instance->appSecret));
            // call Autentification endpoint.
            $response = $this->guzzleHttpService->authenticate();
            $jsonResponse = json_decode($response);

            // Récupération du token de sécurité.
            $developerAccessToken = $jsonResponse->access_token;

            static::$instance->tokenDate = time() + $jsonResponse->expires_in;  // initialize the new expiration date of token.
            static::$instance->accessToken = $developerAccessToken;
            //	static::$instance->refreshToken =  $jsonResponse->refresh_token;
            return static::$instance->accessToken;
        }

    }

   /*
    * Demande denvoie de sms unique a plusieurs numeros.
    * use to send complex type of sms: single one-to-one, single one-to-many, many-to-many, complex many-to many
    */
    // Request
    // {
    //     "body": "Vous avez une préoccupation ou une suggestions à nous faire, laissez-nous un message. Nous nous efforçons de vous répondre dans un délai de 1 à 2 jours ouvrables."
    // }

    //  Response
    //  {
    //     "code": 200,
    //     "status": "success",
    //     "data": {
    //         "originalText": "Vous avez une préoccupation ou une suggestions à nous faire, laissez-nous un message. Nous nous efforçons de vous répondre dans un délai de 1 à 2 jours ouvrables.",
    //         "size": 162,
    //         "preview": {
    //             "textPreview": "Vous avez une préoccupation ou une suggestions à nous faire, laissez-nous un message. Nous nous efforçons de vous répondre dans un délai de 1 à 2 jours ouvrables.",
    //             "encoding": "Unicode", //or GSM
    //             "messageCount": 3,
    //             "charactersRemaining": 36,
    //             "charset": 2             //  Number of unicode caracter found
    //         }
    //     }
    // }
    public function requestPreviewSms($body)
    {
       
        static::$instance->guzzleHttpService->setRequestBody(array("body" => $body));

        $response = static::$instance->guzzleHttpService->requestPreviewSms(static::$instance->accessToken); // request preview de sms.
        $jsonResponse = json_decode($response);                                                 // Décodage du json renvoyé.

        //echo $jsonResponse;
        return $response;
    }

    /*
    * Demande denvoie de sms unique a plusieurs numeros.
    * use to send complex type of sms: single one-to-one, single one-to-many, many-to-many, complex many-to many
    */
    public function requestSimpleSms($destinataires, $body)
    {
        $messages = [];
        $message = [
            "destinations"=> $destinataires, "body" => $body
        ];
        array_push($messages,$message);

        //optional buldId is use to trac delivery repport
        static::$instance->guzzleHttpService->setRequestBody(array("bulkId" => time(), "messages" => $messages));

        $response = static::$instance->guzzleHttpService->requestSendSimpleSms(static::$instance->accessToken);      // Demande d'envoie de sms.
        $jsonResponse = json_decode($response);                                                                      // Décodage du json renvoyé.

        //echo $jsonResponse;
        return $response;
    }

    /*
    * Demande de solde adréssé à un client possédant un numéro de téléphone.
    */
    public function requestGetSmsBalance()
    {
       // echo "ACCESS TOKEN IS:" . static::$instance->accessToken;
        $response = static::$instance->guzzleHttpService->requestGetSmsBalance(static::$instance->accessToken); // Demande d'envoie de sms.
        $jsonResponse = json_decode($response);                            // Décodage du json renvoyé.
       // echo $jsonResponse;
        return $response;
    }

    
    /*
    * Demande de solde adréssé à un client possédant un numéro de téléphone.
    */
    public function requestGetSmsList()
    {
        $response = static::$instance->guzzleHttpService->requestGetSmsList(static::$instance->accessToken);  // Demande d'envoie de sms.
        $jsonResponse = json_decode($response);                                                                  // Décodage du json renvoyé.
        //echo $jsonResponse;
        return $response;
    }
}

?>
 
