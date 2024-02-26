<?php
	require_once 'vendor/autoload.php';
	use GuzzleHttp\Client;

	class GuzzleHttpService {
		protected $appId;
		protected $oauthData;
		protected $accessToken;
		protected $simpleSmsData;
		private $client;
		public function __construct($baseUrl, $appId)
		{
			$this->client =  new Client(['base_uri' => $baseUrl]);
			$this->appId = $appId;
		}

		public function setOauthData ($oauthData)
		{
		   $this->oauthData = $oauthData;
		}

		public function setSimpleSmsData ($simpleSmsData)
		{
		   $this->simpleSmsData = $simpleSmsData;
		}

	   /*
		* Requête d'authentification du développeur
		* pour récupération de l'ACCESS TOKEN de sécurité.
		* methode: POST
		*/
		public function authenticate()
		{
			$raw_response = $this->client->post('/v1/token',[
				'headers' => [ 'Content-Type' => 'application/json' ],
				'body' => json_encode($this->oauthData),
			  ]);
			//echo $raw_response->getBody();
			//echo $raw_response->getStatusCode();
			//echo $raw_response->getHeader('content-type')[0];
			return $raw_response->getBody()->getContents();
		}
		
	   /*
		* Requête d'envoie de sms.
		* methode: POST
		*/
		public function requestSendSimpleSms($accessToken) {
			$raw_response = $this->client->post('/v1/sms/simple',[
				'headers' => [ 'Content-Type' => 'application/json','Authorization' => 'Bearer ' . $accessToken ],
				'body' => json_encode($this->simpleSmsData),
			]);

			//echo $raw_response->getBody();
			//echo $raw_response->getStatusCode();
			//echo $raw_response->getHeader('content-type')[0];
			return $raw_response->getBody()->getContents();
 		}

	   /*
		* Requête interogation du solde sms.
		* methode: GET
		*/
		public function requestGetSmsBalance($accessToken)
		{
			$raw_response = $this->client->get('/balance/'.$this->appId.'/messaging', [
				'headers' => [ 'Authorization' => 'Bearer ' . $accessToken ]
			  ]);
			echo $raw_response->getBody();
			return $raw_response->getBody()->getContents();
		}

		
	   /*
		* Requête interogation du solde sms.
		* methode: GET
		*/
		public function requestGetSmsList($accessToken)
		{
			$raw_response = $this->client->get('/v1/sms/'.$this->appId.'/list', [
				'headers' => [ 'Authorization' => 'Bearer ' . $accessToken ]
			  ]);
			echo $raw_response->getBody();
			return $raw_response->getBody()->getContents();
		}
	}
?>
