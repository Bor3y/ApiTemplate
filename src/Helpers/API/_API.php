<?php

namespace App\Helpers\API;

class API
{
    /**
     * Checks if the client info from the request matches the client info used in .env file
     *
     * @param  string $clientId     Client id from the request
     * @param  string $clientSecret Client secret from the request
     * @return boolean
     */
    public static function validateClientInputs( $clientId, $clientSecret )
    {
        $client = self::getActiveClient();

        return ($client != null)
               && (!empty($clientId))
               && (!empty($clientSecret))
               && ($clientId == $client->id)
               && ($clientSecret == $client->secret);
    }

    /**
     * Gets the client info from the .env file
     *
     * @return object
     */
    public static function getActiveClient()
    {
        $clientId = config('auth.client_id');
        $clientSecret = config('auth.client_secret');

        return (object) [
            'id' => $clientId,
            'secret' => $clientSecret
        ];
    }

    /**
     * Formats JSON response from message, statusCode and respondContents
     *
     * @param  string       $message      Message to be returned
     * @param  integer      $statusCode   StatusCode to be returned
     * @param  array|null   $array        JSON response to be returned
     * @return Symfony\Component\HttpFoundation\Response
     */
    public static function respond( $message, $statusCode, $array = null )
    {
        if(is_null($array)) {
            $array = array();
        }

        $messageKey = 'message';

        if(strval($statusCode)[0] != 2) {
            $messageKey = 'error_message_sentence';
        }

        if(!empty($message)) {
            if(is_array($array))
                $array[$messageKey] = $message;
            else
                $array->$messageKey = $message;
        }

        return response()->json($array, $statusCode, [], JSON_UNESCAPED_UNICODE);
    }
}
