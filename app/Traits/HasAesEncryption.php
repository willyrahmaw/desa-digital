<?php

namespace App\Traits;

use App\Helpers\AesSecurity;

trait HasAesEncryption
{
    /**
     * Get the list of attributes that should be encrypted with AES-256.
     */
    public function getEncryptedAttributes(): array
    {
        return property_exists($this, 'encrypted') ? $this->encrypted : [];
    }

    /**
     * Determine if an attribute is marked for AES encryption.
     */
    public function isEncryptedAttribute(string $key): bool
    {
        return in_array($key, $this->getEncryptedAttributes(), true);
    }

    /**
     * Override getAttribute to automatically decrypt AES encrypted columns.
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if ($this->isEncryptedAttribute($key) && is_string($value)) {
            return AesSecurity::decrypt($value);
        }

        return $value;
    }

    /**
     * Override setAttribute to automatically encrypt AES encrypted columns.
     */
    public function setAttribute($key, $value)
    {
        if ($this->isEncryptedAttribute($key) && is_string($value) && $value !== '') {
            $value = AesSecurity::encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Override attributesToArray to ensure encrypted attributes are decrypted in array/JSON serialization.
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        foreach ($this->getEncryptedAttributes() as $key) {
            if (isset($attributes[$key]) && is_string($attributes[$key])) {
                $attributes[$key] = AesSecurity::decrypt($attributes[$key]);
            }
        }

        return $attributes;
    }

    /**
     * Get raw ciphertext value from database attribute.
     */
    public function getRawCiphertext(string $key): ?string
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Create a new Eloquent query builder for the model with AES encryption awareness.
     */
    public function newEloquentBuilder($query)
    {
        return new \App\Helpers\AesEloquentBuilder($query);
    }
}
