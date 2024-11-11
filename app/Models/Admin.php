<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'Admin';

    private static $secret = 'bT7!dFs9Zp@kL%2wXy&CvGhQjN3r$BmP';

    public static function generateToken($payload)
    {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];

        $base64UrlHeader = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
        $base64UrlPayload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');

        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", self::$secret, true);
        $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }

    public static function verifyToken($token)
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return false; // Invalid token format
        }

        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $parts;

        // Re-generate the signature
        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", self::$secret, true);
        $expectedSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        // Verify that the provided signature matches the expected signature
        if ($base64UrlSignature !== $expectedSignature) {
            return false; // Signature mismatch
        }

        // Decode the payload
        $payload = json_decode(base64_decode(strtr($base64UrlPayload, '-_', '+/')), true);

        // Ensure payload contains required fields
        if (!isset($payload['username']) || !isset($payload['password'])) {
            return false; // Payload is missing username or password
        }



        return $payload;
    }
}
