<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('twilio-php/Services/Twilio.php');
require

class Diagnoser extends CI_Controller {
    
    function __construct() {
		parent::__construct();
	}
 
    public function index() {
        
        $prompt = "\n\nPress 1 for fever,\n
                   2 for headache,\n
                   3 for swelling,\n
                   4 for cough,\n
                   5 for pain,\n
                   6 for nausea,\n
                   7 for night sweats,\n
                   8 for diarrhea,\n
                   9 for fatigue,\n
                   10 for itch/rash,\n
                   11 for loss of appetite,\n
                   12 for shortness of breath,\n
                   13 for vomiting,\n
                   14 for dehydration,\n
                   15 for chills,\n
                   16 for bloody stool,\n
                   17 for running nose,\n
                   18 for sore throat,\n";
        
        
    }
    
}