<?php
function generateTicketCode($length = 13) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $ticketCode = '';

    for ($i = 0; $i < $length; $i++) {
        $ticketCode .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $ticketCode;
}

$ticketCode = generateTicketCode();
echo $ticketCode;
