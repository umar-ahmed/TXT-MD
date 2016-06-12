<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require('twilio-php/Services/Twilio.php');
require('./sag/Sag.php');

class Receive extends CI_Controller {
    
    function __construct() {
		parent::__construct();
	}
 
    public function index() {
                
        // Twilio
        
        $response = new Services_Twilio_Twiml();
        
        $body = $_REQUEST['Body'];
        $zip = $_REQUEST['FromZip'];
        $from = $_REQUEST['From'];
                
        $result = preg_replace("/[^A-Za-z0-9]/u", " ", $body);
        $result = trim($result);
        $result = strtolower($result);
        
    
        $myfile = fopen("test.txt", "r+");
        $content = fread($myfile, filesize("test.txt"));
        fwrite($myfile, "\n" . $result . ' ' . $zip);
        fclose($myfile);
        
        
        // Cloudant
        
        $user = "newtonsfourthlaw";
        $pass = "cultivate";
        
        $sag = new Sag('newtonsfourthlaw.cloudant.com');
        $sag->setDatabase('test');
        
        $sag->login($user, $pass);
        
        // Get the old document
        $doc = $sag->get('4169122539')->body;
        //$doc = json_decode( $temp );
        
        echo json_encode($doc);
        
        
        
        /*
        $id = json_decode($doc)->{'id'};
        $rev = json_decode($doc)->{'rev'};

        // Change the gender and keep track of revision
        $doc['gender'] = "f";
        $doc['_rev'] = $rev;
        
        // PUT tot server
        $putDocRequest = $sag->put("$id", array(), json_encode($doc));
        $putDocResponse = $putDocRequest->send();     
        
        */
        
        
    }
    
}
?>

