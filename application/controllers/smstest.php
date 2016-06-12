<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('twilio-php/Services/Twilio.php');

class Smstest extends CI_Controller {
	
	function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$sid = "AC6354b1b9319c0d5b763637dc9382ccbf"; // Your Account SID from www.twilio.com/user/account
        $token = "dd3bf072b26b751ab32b0da92079f861"; // Your Auth Token from www.twilio.com/user/account
		
		$http = new Services_Twilio_TinyHttp(
            'https://api.twilio.com',
            array('curlopts' => array(
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            ))
        );
		$client = new Services_Twilio($sid, $token, "2010-04-01", $http);
		
		//first time user texts service
		$response = new Services_Twilio_Twiml();
        $body = $_REQUEST['Body'];
        $zip = $_REQUEST['FromZip'];
        $from = $_REQUEST['From'];
        
		//check if new user
		$file_name = $from . ".txt";
		if(!file_exists($file_name)){
			file_put_contents("Users.txt", $from . " " .  $zip . "\n", FILE_APPEND);
			file_put_contents($file_name, "SMS", FILE_APPEND);
			$message = "Press 1 for English,\n2 for Kiswahili,\n3 for Kikuyu ,\n4 for Kibet ,\n5 for Dholuo ,\n6 for Ekegusii ";
			$message = $client->account->messages->sendMessage('+16475593401', $from, $message);
		}
		else{
			$current = file_get_contents($file_name);
			$numSpaces = substr_count($current, ' ');
			$lang = "1";
			if($numSpaces == 0){
				$lang = $body;
				file_put_contents($file_name, " " . $body , FILE_APPEND);
				$numSpaces++;
			}
			if($numSpaces == 1){
				file_put_contents($file_name, " GEN" , FILE_APPEND);
				$message = "Press 1 for Male,\n2 for Female";
				$message = $client->account->messages->sendMessage('+16475593401', $from, $message);
			}
		   if($numSpaces == 2){
				file_put_contents($file_name, " " . $body , FILE_APPEND);
				$numSpaces++;
			}
		    if($numSpaces == 3){
				file_put_contents($file_name, " AGE" , FILE_APPEND);
				$message = "Enter age";
				$message = $client->account->messages->sendMessage('+16475593401', $from, $message);
			}
		    if($numSpaces == 4){
				file_put_contents($file_name, " " . $body , FILE_APPEND);
				$numSpaces++;
			}
		    if($numSpaces == 5){
				file_put_contents($file_name, " SYM" , FILE_APPEND);
				$message = "\n\nPress 1 for fever,\n2 for headache,\n3 for swelling,\n4 for cough,\n5 for pain,\n6 for nausea,\n7 for night sweats,\n8 for diarrhea,\n9 for fatigue,\n10 for itch/rash,\n11 for loss of appetite,\n12 for shortness of breath,\n13 for vomiting,\n14 for dehydration,\n15 for chills,\n16 for bloody stool,\n17 for running nose,\n18 for sore throat";				
				$message = $client->account->messages->sendMessage('+16475593401', $from, $message);
			}
            if($numSpaces == 6){
			    $parts = preg_split('/\s+/', $body);
				$symFile = $from . "symptoms" . ".txt";
				
				$symps = array("","fever","headache","swelling","cough","pain","nausea","night sweats","diarrhea","fatigue","itch/rash"," loss of appetite","shortness of breath","vomiting","dehydration","chills","bloody stool","running nose","sore throat");
				for($i = 0; $i < count($parts); $i++){
					file_put_contents($symFile,  $symps[(int)$parts[$i]] . "\n", FILE_APPEND);
				}
				
				$diagnosis = "safe";
				$nearestFacility = "Sick Kids Hospital: ";
				$diagnosis = "malaria";
				file_put_contents($file_name, " " . $diagnosis , FILE_APPEND);
				if($diagnosis != "safe"){
					$message = "Your symptoms match those of " . $diagnosis;	
					$doctor = "The closest medical facility is " . $nearestFacility;
					$confirmation = "Book an appointment?";
					$message = $client->account->messages->sendMessage('+16475593401', $from, $message . "\n" . $doctor . "\n" . $confirmation . "\n");
				}
				else{
					//treatment
				}

			}

		}
		
		
	}

}

?>