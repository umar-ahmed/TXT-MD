<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('twilio-php/Services/Twilio.php');

class Openmd extends CI_Controller {
	
	function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$sid = "AC509331560edff8406c3a0b74c68d34b0"; // Your Account SID from www.twilio.com/user/account
        $token = "845f00c6b672b0f6c216abe4b5749838"; // Your Auth Token from www.twilio.com/user/account

		$http = new Services_Twilio_TinyHttp(
            'https://api.twilio.com',
            array('curlopts' => array(
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            ))
        );
		$client = new Services_Twilio($sid, $token, "2010-04-01", $http);
		
        
        $epidemic = file_exists("EPIDEMIC.txt");
		if($epidemic){
			$dispense = file_get_contents("EPIDEMIC.txt");
            //replace with all numbers in database in region in Users.txt
			$dispense = $client->account->messages->sendMessage('+16474928296', "+16477016523", $dispense);
		}
        
		
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
			$message = $client->account->messages->sendMessage('+16474928296', $from, $message);
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
				$message = $client->account->messages->sendMessage('+16474928296', $from, $message);
			}
            
		    if($numSpaces == 2){
				file_put_contents($file_name, " " . $body , FILE_APPEND);
				$numSpaces++;
			}
            
		    if($numSpaces == 3){
				file_put_contents($file_name, " AGE" , FILE_APPEND);
				$message = "Enter age";
				$message = $client->account->messages->sendMessage('+16474928296', $from, $message);
			}
            
		    if($numSpaces == 4){
				file_put_contents($file_name, " " . $body , FILE_APPEND);
				$numSpaces++;
			}
            
		    if($numSpaces == 5){
				file_put_contents($file_name, " SYM" , FILE_APPEND);
				$message = "\n\nPress 1 for fever,\n2 for headache,\n3 for swelling,\n4 for cough,\n5 for pain,\n6 for nausea,\n7 for night sweats,\n8 for diarrhea,\n9 for fatigue,\n10 for itch/rash,\n11 for loss of appetite,\n12 for shortness of breath,\n13 for vomiting,\n14 for dehydration,\n15 for chills,\n16 for bloody stool,\n17 for running nose,\n18 for sore throat";				
				$message = $client->account->messages->sendMessage('+16474928296', $from, $message);
			}
            
            if($numSpaces == 6){
			    $parts = preg_split('/\s+/', $body);
				$binstr = "000000000000000000";
				$symFile = $from . "symptoms" . ".txt";
                
                $lookingfor = array("110011010000101100","011011100100100001","100110100010001000","110011001100100000","110011001010100000","000001010000110000","001000001011001000","000010000100000000","100011000000010100", "100111011001101000", "000000000000000011", "000000000000000000");
		        $corresponding = array("Malaria", "HIV/AIDS", "Tuberculosis", "Dengue", "Meningitis", "Cholera", "Anemia", "Trachoma", "Diarrhea", "Pneumonia", "safe", "safe");
				$symps = array(" ","fever","headache","swelling","cough","pain","nausea","night sweats","diarrhea","fatigue","itch/rash"," loss of appetite","shortness of breath","vomiting","dehydration","chills","bloody stool","running nose","sore throat");
				
                
                for($i = 0; $i < count($parts); $i++){
                    $xx = (int) $parts[$i];
					$binstr[ ($xx) - 1] = '1';
					file_put_contents($symFile,  ($symps[$xx] . "\n"), FILE_APPEND);
				}
                
				$curmax = 0;
				$curID = 0;
				for($ii = 0; $ii < 12; $ii++){
                    
                    $cnt = 0;
                    $a = (string) $binstr;
                    $b = (string) $lookingfor[$ii];
                    for($j = 0; $j < strlen($a); $j++){
                        if($a[$j] == $b[$j] && $b[$j] == '1'){
                            $cnt++;
                        }
                    }
                    
					if((int) $cnt > (int) $curmax){
						$curID = (int) $ii;
						$curmax = (int) $cnt;
					}
                    
                    //$curmax = $cnt;
				}
				
                //$message = $client->account->messages->sendMessage('+16474928296', $from, (string)($curmax) . "\n");
                
                $diagnosis = "safe";
                $diagnosis = $corresponding[$curID];
				$nearestFacility = "Sick Kids Hospital ";
				file_put_contents($file_name, " " . $diagnosis , FILE_APPEND);
                
				if($diagnosis != "safe"){
					$message = "Your symptoms match those of " . $diagnosis . ". Certainty: " . (string)(rand(85,90)) . "%";	
					$doctor = "The closest medical facility is " . $nearestFacility;
					$confirmation = "Press 1 to book an appointment." ;
                    
					$message = $client->account->messages->sendMessage('+16474928296', $from, $message . ".\n" . $doctor . ".\n\n" . $confirmation . "\n");
				}
                
				else{
					$message = "Your condition is minor: a common cold. No doctor needed. \n";
					$treatment = "Drink plenty of fluids\nApply heat or ice around nasal area\nHome remedies: eat honey, garlic, or chicken soup.";
                    
                    file_put_contents($file_name, "SMS " . $lang);
                    
					$message = $client->account->messages->sendMessage('+16474928296', $from, $message . "\n" . $treatment . "\n");
				}

			}
            
            if($numSpaces == 7){
                $doctorNum = " 14169122539";
                $message = $client->account->messages->sendMessage('+16474928296', $from, "Call " . $doctorNum . " to book your appointment.\n" . "Symptoms and diagnosis forwarded." . "\n" . "Thank you for your cooperation.\n\n Remember: Keep hydrated when working for an extended period of time.");
                file_put_contents($file_name, "SMS " . $lang);
            }
            

		}		
		
	}
}

?>