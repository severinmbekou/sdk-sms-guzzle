# Solution of mobile Money payment in Cameroon

SDK Standard Edition
========================

Welcome to the SDK Standard Edition - a simple-functional
application that you can use as the skeleton for your new applications based on Mobile Money .

What's inside?
--------------

** Curl Service **
** Custom API authentication Service **
** index.php **


Installation
============

1. Create your developer account on http://api.3ibusiness.com

2. Connect to the web application http://api.3ibusiness.com/login

   2.1 create your application
   
   2.2 validate your phone number
   
   2.3 Pay your setup fee
   
   2.4 copy you client_id and client_secret that you are going to use to obtain your access token that allow you to call API


3. How to install SDK

   3.1 clone SDK
       **git clone http://github.com/3ibusiness/sdkibusiness.git**
       
   3.2 download SDK
   
   3.3 unzip into your server either Xampp or Wampp

4. Update **index.php*

   4.1 paste your client_id, client_secret, username and password in other to obtain access token in the function.
    **CustumAPI::getInstance(client_id, client_secret, username, password);**
    
   4.2 Fill the Client Phone Number and the amount for the RequestPayment.

5. Start and open it in the browser

   **run Xampp or Wamp**

   Then go to the adress: [http://localhost/name_of_project]


DEVELOPMENT TRICKS Base Url : http://api.3ibusiness.com
=============================

1) Test the API with clientId & clientSecret

     a) Récupérer ses credentials dans la zone 'Applications' sur le menu sur le site: http://localhost:8000/transaction/
    	* Client Id
    	* Client Secret

    b) Obtenir un token de connection pour faire les appels en tappant ce lien avec les variables à modifier:
    
    **Using Browser**
        [GET] /oauth/v2/token?client_id=client_id_du_developpeur&client_secret=client_secret_du_developpeur&username=username_du_developpeur&password=password_du_developpeur&grant_type=password
        
    **Using Postman or Tools for Rest API**
        [POST] /oauth/v2/token
        body: 
        {
         client_id:client_id_du_developpeur
         client_secret:client_secret_du_developpeur
         username:username_du_developpeur
         password:password_du_developpeur
         grant_type:password
        }

    La réponse donnée par la sécurité fiable d'OAuth sera de la forme:
    {
     access_token:access_token_of_developpeur_with_a_validity,
     expires_in:86400,
     token_type:Bearer,
     scope:user,
     refresh_token:refresh_token_of_developpeur_with_a_validity
    }

   c) Ensuite récupéré le 'access_token': c'est la clé pour faire les appels.

   d) Url de test de l'API pour les developpeurs:
   
    **Using Browser**
        [GET] /api/ping?access_token=access_token_du_developpeur
        
    **Using Postman or Tools for Rest API**
        [POST] /api/ping
        headers{
            Authorization:Bearer access_token_du_developpeur
        }

    the Response should be in the form:
        {
            error: no,
            message: Valid developer account,
            application: AppDemo,
            activated: true,
            phoneNumber: 677925286,
            fullName: Demo Access
        }
   So you can enjoy your future calls alone.

    e) Test payment enpoint
     
     /api/mtn/momo/v2/requestpayment

    **Using Browser**
        [GET] /api/mtn/momo/v2/requestpayment?access_token=access_token_du_developpeur&amount=50&phoneNumber=677925286
        
    **Using Postman or Tools for Rest API**
        [POST] /api/mtn/momo/v2/requestpayment
        headers{
            Authorization:Bearer access_token_du_developpeur
        }
     body: {
      amount:100,
      phoneNumber:677925286
     }

Response of request

case 0 Request Time out 120 seconde
{
	'code'  : '120', 
	'message' : 'Request timeout: 120 seconds'
}

case 1 Invalid post information 
{
	'code' : '013', 
	'phone' : 'Phone number must not be null or empty',
	'amount' : 'Amount must not be null or empty',
	'message' : 'ErrorParams: Invalid Amount or PhoneNumber'
}

case 2 succeed redraw from client and succed deposite to developer account
{
	'code' :'200', 
	'paymentIdentifier' : 12345678901, 
	'depositIdentifier' : 23454567674, 
	'message' : 'Paiement MoMo effectué avec succès. Numéro de transaction ' . $processingNumber . '.'
}

case 3 succeed redraw from client but not deposite to developer account
{
	'code' :'405', 
	'paymentIdentifier' : 12345678901,
	'depositIdentifier' : 000-000-000,	
	'message' : 'Sorry, client is deducted but you must validate the deposit in your panel'
}

case 4 invalid owner information //
{
	'code' : '012', 
	'phone' : 'Phone number must be verified before any request',
	'setupfees' : 'Setup fees is not yet paid',
	'message' : 'ErrorParams: Invalid Amount or PhoneNumber'
}

Enjoy!

Get more about api https://documenter.getpostman.com/view/5952333/RzfZMsJp
