<?php
	require_once 'vendor/autoload.php';
	use GuzzleHttp\Client;

	class GuzzleHttpService {
		protected $appId;
		protected $oauthData;
		protected $accessToken;
		protected $requestBody;
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

		public function setRequestBody ($requestBody)
		{
		   $this->requestBody = $requestBody;
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
		public function requestPreviewSms($accessToken) {
			echo json_encode($this->requestBody);
			$raw_response = $this->client->post('/v1/sms/preview',[
				'headers' => [ 'Content-Type' => 'application/json'
			//	,'Authorization' => 'Bearer ' . $accessToken 
			],
				'body' => json_encode($this->requestBody),
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
			echo json_encode($this->requestBody);
			$raw_response = $this->client->post('/v1/sms/send',[
				'headers' => [ 'Content-Type' => 'application/json','Authorization' => 'Bearer ' . $accessToken ],
				'body' => json_encode($this->requestBody),
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

				
	   /*
		* Requête liste des rapports.
		* methode: GET
		*/
		public function requestGetReportList($accessToken)
		{
			//$bulkId as query param is optional
			$raw_response = $this->client->get('/v1/sms/'.$this->appId.'/reports?bulkId=', [
				'headers' => [ 'Authorization' => 'Bearer ' . $accessToken ]
			  ]);
			echo $raw_response->getBody();
			return $raw_response->getBody()->getContents();
		}

						
	   /*
		* Requête single rapport by messageId.
		* methode: GET
		*/
		public function requestGetSingleReport($accessToken)
		{
			$messageId = "17e4eb6a-8194-4ee8-af4d-0af3674d3d80"; //ID of the message
			$raw_response = $this->client->get('/v1/sms/'.$this->appId.'/reports/'.$messageId, [
				'headers' => [ 'Authorization' => 'Bearer ' . $accessToken ]
			  ]);
			echo $raw_response->getBody();
			return $raw_response->getBody()->getContents();
		}
	}
?>
