<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require('twilio-php/Services/Twilio.php');

class Send extends CI_Controller {
    
    function __construct() {
		parent::__construct();
	}
 
    public function index() {
        $sid = "AC6354b1b9319c0d5b763637dc9382ccbf"; // Your Account SID from www.twilio.com/user/account
        $token = "dd3bf072b26b751ab32b0da92079f861"; // Your Auth Token from www.twilio.com/user/account

        $from = '+16475593401';
        $to = '+14162744792';
        $message = "\n\nPress 1 for fever,\n2 for headache,\n3 for swelling,\n4 for cough,\n5 for pain,\n6 for nausea,\n7 for night sweats,\n8 for diarrhea,\n9 for fatigue,\n10 for itch/rash,\n11 for loss of appetite,\n12 for shortness of breath,\n13 for vomiting,\n14 for dehydration,\n15 for chills,\n16 for bloody stool,\n17 for running nose,\n18 for sore throat";

        $http = new Services_Twilio_TinyHttp(
            'https://api.twilio.com',
            array('curlopts' => array(
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            ))
        );

        $client = new Services_Twilio($sid, $token, "2010-04-01", $http);
        
        
        $message = $client->account->messages->sendMessage($from, $to, $message);

        echo $message->sid;
        
    }
    
}

?>