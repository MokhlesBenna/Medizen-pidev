<?php

namespace App\Controller;

require_once 'C:/Users/DELL/Desktop/finalwork/MediZen2/vendor/autoload.php';

use Twilio\Rest\Client;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class SMSController extends AbstractController
    {
        #[Route('/SMS/{msg}', name: 'app_SMS')]
        public function index($msg): Response
        {
            // Your Account SID and Auth Token from twilio.com/console
            // To set up environmental variables, see http://twil.io/secure
            $account_sid = 'AC2d0667e7b2c66cb9e7bade3584b4a5b7';
            $auth_token = 'c721549e2145be390ed5fbaea374bfca';
            // In production, these should be environment variables. E.g.:
            // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

            // A Twilio number you own with SMS capabilities
            $twilio_number = "+19284400493";

            $client = new Client($account_sid, $auth_token);

            $client->messages->create(
                // Where to send a text message (your cell phone?)
                '+21626653094',
                array(
                    'from' => $twilio_number,
                    'body' => $msg
                )
            );
            return new Response('SMS sent successfully');
        }


    }