<?php

require 'vendor/autoload.php';

// Set your Stripe API key here
\Stripe\Stripe::setApiKey('sk_test_51OqoS0G1DR5SKi86xYIFdz8d62mBHF1uQNV29VnQUECiSI44HVq1Y15yYmXLyc2jZq4DVZIUaX9gRCSp7DDWFCcL00aDAFVNpO');

$stripe = new \Stripe\StripeClient();

$checkout_session = $stripe->checkout->sessions->create([
    'line_items' => [[
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => 'T-shirt',
            ],
            'unit_amount' => 2000,
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'http://localhost:4242/success',
    'cancel_url' => 'http://localhost:4242/cancel',
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);
