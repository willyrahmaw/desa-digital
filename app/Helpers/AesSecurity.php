<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class AesSecurity
{
    private const CIPHER = 'aes-256-cbc';
    private const PREFIX = 'AES256:';

    /**
     * Get the 256-bit encryption key derived from application key.
     */
    private static function getKey(): string
    {
        $appKey = config('app.key');
        if (Str::startsWith($appKey, 'base64:')) {
            $key = base64_decode(substr($appKey, 7));
        } else {
            $key = (string)$appKey;
        }

        return hash('sha256', $key, true);
    }

    /**
     * Encrypt a plain string deterministically using AES-256-CBC.
     * Guaranteed that identical plaintext produces identical ciphertext,
     * maintaining foreign keys and exact SQL index lookups.
     */
    public static function encrypt(?string $plaintext): ?string
    {
        if ($plaintext === null || $plaintext === '') {
            return $plaintext;
        }

        // Avoid double encryption
        if (self::isEncrypted($plaintext)) {
            return $plaintext;
        }

        $key = self::getKey();
        // Deterministic IV generated via HMAC-SHA256 of plaintext
        $iv = substr(hash_hmac('sha256', $plaintext, $key, true), 0, 16);

        $encrypted = openssl_encrypt(
            $plaintext,
            self::CIPHER,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        if ($encrypted === false) {
            return $plaintext;
        }

        // Format: AES256:<base64(iv . ciphertext)>
        return self::PREFIX . base64_encode($iv . $encrypted);
    }

    /**
     * Decrypt AES-256-CBC ciphertext back to original plaintext.
     */
    public static function decrypt(?string $ciphertext): ?string
    {
        if ($ciphertext === null || $ciphertext === '') {
            return $ciphertext;
        }

        if (!self::isEncrypted($ciphertext)) {
            return $ciphertext; // Already plain text
        }

        $payload = substr($ciphertext, strlen(self::PREFIX));
        $decoded = base64_decode($payload, true);

        if ($decoded === false || strlen($decoded) < 17) {
            return $ciphertext;
        }

        $iv = substr($decoded, 0, 16);
        $rawCipher = substr($decoded, 16);
        $key = self::getKey();

        $decrypted = openssl_decrypt(
            $rawCipher,
            self::CIPHER,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return $decrypted !== false ? $decrypted : $ciphertext;
    }

    /**
     * Check if a given string is AES encrypted.
     */
    public static function isEncrypted(?string $value): bool
    {
        if ($value === null) {
            return false;
        }

        return str_starts_with($value, self::PREFIX);
    }

    /**
     * Mask sensitive numbers for privacy (e.g. 3507040101900001 -> 350704******0001).
     */
    public static function mask(?string $value, int $unmaskedStart = 6, int $unmaskedEnd = 4): string
    {
        if (!$value) {
            return '-';
        }

        // If it's encrypted, decrypt first before masking
        $plain = self::decrypt($value);
        $len = strlen($plain);

        if ($len <= ($unmaskedStart + $unmaskedEnd)) {
            return $plain;
        }

        $prefix = substr($plain, 0, $unmaskedStart);
        $suffix = substr($plain, -$unmaskedEnd);
        $maskLength = $len - ($unmaskedStart + $unmaskedEnd);

        return $prefix . str_repeat('*', $maskLength) . $suffix;
    }
}
