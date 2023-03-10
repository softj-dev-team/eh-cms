<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class TokenNotFoundException extends InvalidArgumentException
{
    public $customMessage;

    /**
     * Constructor
     */
    public function __construct($message = null) {
        if ($message) {
            $this->customMessage = $message;
        } else {
            $config = config('error-messages');
            $this->customMessage = $config['account']['messages']['token_not_found'];
        }
    }

    public function report() {
        $exceptionMessage = $this->customMessage;
        Log::warning($exceptionMessage);
    }

    public function render() {
        // Sending 400 in API responses
        $payload = $this->customMessage;

        return response()->json($payload, Response::HTTP_BAD_REQUEST);
    }
}
