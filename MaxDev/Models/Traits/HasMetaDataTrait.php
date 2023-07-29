<?php

namespace MaxDev\Models\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use function count;
use function is_array;
use function is_object;
use function is_string;
use function strlen;
use const FILTER_NULL_ON_FAILURE;
use const FILTER_VALIDATE_BOOLEAN;
use const FILTER_VALIDATE_INT;

trait HasMetaDataTrait
{
    public bool $useOldMetadata = true;

    /**
     * Search for specific metadata attribute using dot notation.
     *
     * @param $metadata_key
     *
     * @return mixed
     * @author Frédéric Trudeau <ftrudeau@pelcro.com>
     *
     */

    protected function initializeHasMetaDataTrait()
    {
        // nothing should be here
        //$this->metadata = ['request' => request()->all()];
    }

    public function hasMetaData($metadata_key)
    {
        if ($metadata = $this->metadata) {
            return Arr::get($metadata, $metadata_key);
        }
    }

    /**
     * Set a metadata attribute using dot notation.
     *
     * @param $options
     * @author Frédéric Trudeau <ftrudeau@pelcro.com>
     *
     */
    public function setMetaData(array $options): void
    {
        $metadata = $this->metadata ?: [];
        foreach ($options as $metadata_key => $metadata_value) {
            Arr::set($metadata, $metadata_key, $metadata_value);
        }
        $this->metadata = $metadata ?: null;
        $this->save();
    }

    /**
     * Setter for 'metadata' attribute.
     *
     * @param $set_metadata
     * @author Frédéric Trudeau <ftrudeau@pelcro.com>
     *
     */
    public function setMetadataAttribute($set_metadata): void
    {
        // Set new metadata to NULL, by default
        $new_metadata = null;

        // Get previous metadata as collection
        if ($previous_metadata = $this->metadata ?: null) {
            $previous_metadata = collect($previous_metadata);
        }

        // Make sure set metadata is an array or object
        if (is_array($set_metadata) || is_object($set_metadata)) {
            // Transform to collection for ease of manipulation
            $set_metadata = collect($set_metadata);

            // Merge previous and set
            if ($previous_metadata && $this->useOldMetadata) {
                $set_metadata = $set_metadata->union($previous_metadata);
            }

            // Check if non-empty
            if ($set_metadata->isNotEmpty()) {
                // Filter out NULL values
                $set_metadata = $set_metadata->filter(static function ($value, $key) {
                    return null !== $value;
                });

                // Transform collection by ...
                $set_metadata->transform(static function ($value, $key) {
                    // ... checking for any value that is an array or object ...
                    if (is_array($value) || is_object($value)) {
                        // ... serialize it
                        $value = serialize($value);
                    } else {
                        // typecast everything else as a string
                        $value = (string)$value;
                    }

                    return $value;
                });

                // Convert collection to Json and set
                $new_metadata = $set_metadata->toJson();
            }
        }

        // Default metadata to NULL
        $this->attributes['metadata'] = $new_metadata;
    }

    /**
     * Getter for 'metadata' attribute.
     *
     * @param $value
     *
     * @return mixed
     *
     * @author Frédéric Trudeau <ftrudeau@pelcro.com>
     */
    public function getMetadataAttribute($value)
    {
        if ($value) {
            // Self.
            $self = $this;

            // Transform to collection for ease of manipulation
            $metadata = is_array($value) ? collect($value) : collect(json_decode($value, true));

            // Return NULL if empty
            if ($metadata->isEmpty()) {
                return null;
            }

            // Transform collection by ...
            $metadata->transform(static function ($value, $key) use ($self) {
                // ... checking for any value that is serialized ...
                if ($self->isSerialized($value)) {
                    // ... unserialize and return it
                    return unserialize($value);
                }

                // Return original value, by default
                return $value;
            });

            // Return metadata
            return $metadata->toArray();
        }

        // Return original value, by default
        return $value;
    }

    /**
     * Search in current model for a key/pair match in metadata.
     *
     * @param $metadata_key
     * @param $metadata_value
     *
     * @return bool
     * @author Frédéric Trudeau <ftrudeau@pelcro.com>
     *
     */
    public function hasMetaDataValue($metadata_key, $metadata_value): bool
    {
        if (($metadata = $this->metadata) && $current_metadata_value = Arr::get($metadata, $metadata_key)) {
            // Handle booleans
            if (($boolean = filter_var($metadata_value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) !== null) {
                if ($boolean) {
                    return
                        1 === $current_metadata_value
                        || '1' === $current_metadata_value
                        || true === $current_metadata_value
                        || 'true' === $current_metadata_value;
                }

                return
                    0 === $current_metadata_value
                    || '0' === $current_metadata_value
                    || false === $current_metadata_value
                    || 'false' === $current_metadata_value;
            }

            // Handle integers
            if ($json_value_to_int = filter_var($metadata_value, FILTER_VALIDATE_INT)) {
                return
                    $current_metadata_value === $json_value_to_int
                    || $current_metadata_value === (string)$json_value_to_int;
            }

            // Handle strings
            return $current_metadata_value === $metadata_value;
        }

        return false;
    }

    /**
     * Scope a query to search in metadata by key/value.
     *
     * @param $query
     * @param $metadata_key
     * @param $metadata_value
     *
     * @return Builder
     * @author Frédéric Trudeau <ftrudeau@pelcro.com>
     *
     */
    public function scopeHasMetaDataKeyWithValue($query, $metadata_key, $metadata_value): Builder
    {
        $regex = '\"' . $metadata_key . '\":\"?' . $metadata_value . '\"?';

        return $query->whereRaw("`metadata` REGEXP '{$regex}'");
        // For JSON columns
        // return $query->where("metadata->{$metadata_key}", '=', $metadata_value);
    }

    /**
     * Scope a query to search in metadata by attribute value NOT in serialized string.
     *
     * @param $query
     * @param $metadata_key
     * @param $metadata_value
     *
     * @return Builder
     * @author Frédéric Trudeau <ftrudeau@pelcro.com>
     *
     */
    public function scopeHasNotMetaDataValue($query, $metadata_key, $metadata_value): Builder
    {
        $regex = '\"' . $metadata_key . '\":\"?' . $metadata_value . '\"?';

        return $query->whereRaw("`metadata` NOT REGEXP '{$regex}'");
        // For JSON columns
        // return $query->where("metadata->{$metadata_key}", '!=', $metadata_value);
    }

    /**
     * Temporary "solution" to avoid posting some metadata keys or values to Stripe because of limitations on their side.
     *
     * @param null|array|string $metadata
     *
     * @return Collection
     *
     * @author Frédéric Trudeau <ftrudeau@pelcro.com>
     */
    public static function processMetaDataBeforeStripePost($metadata): ?Collection
    {
        if ($metadata) {
            // Transform to collection for ease of manipulation
            //  failed with error: json_decode() expects parameter 1 to be string, array given in file C:\LARAGON\www\pelcro\app\Models\Traits\HasMetaDataTrait.php on line 292
            if (is_string($metadata)) {
                $metadata = collect(json_decode($metadata, true));
            } else {
                $metadata = collect($metadata);
            }

            // Foreach key/value pair ...
            foreach ($metadata as $key => $value) {
                // ... reject keys that exceeds Stripe's limit of 40 characters
                if (strlen($key) > 40) {
                    unset($metadata[$key]);
                }

                // ... reject values that reached Stripe's limit of 500 characters
                if (strlen($value) >= 500) {
                    unset($metadata[$key]);
                }
            }

            return count($metadata) ? $metadata : null;
        }

        return null;
    }

    /**
     * Access Model's metadata and return all the key value pairs after excluding Pelcro's reserved keys.
     *
     * @param $model
     *
     * @return array
     *
     * @author Mina <mfarag@pelcro.com>
     */
    public function getModelMetaDataPelcroKeysExcludedAsFlatArray($model): array
    {
        $flat_metadata = [];

        if (!isset($model->metadata)) {
            return $flat_metadata;
        }

        $metadata = collect($model->metadata);

        if ($metadata->isEmpty()) {
            return $flat_metadata;
        }

        $metadata->map(function ($value, $key) use (&$flat_metadata) {
            if (!str_starts_with($key, 'pelcro') && !is_array($value) && !is_array($key)) {
                $flat_metadata[$key] = $value;
            }
        });

        return $flat_metadata;
    }

    /**
     * Check if data is serialized.
     *
     * @param $data
     * @param bool $strict
     *
     * @return bool
     * @see https://developer.wordpress.org/reference/functions/is_serialized/
     *
     * @author Wordress
     *
     */
    private function isSerialized($data, $strict = true)
    {
        // if it isn't a string, it isn't serialized.
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if ('N;' == $data) {
            return true;
        }
        if (strlen($data) < 4) {
            return false;
        }
        if (':' !== $data[1]) {
            return false;
        }
        if ($strict) {
            $lastc = substr($data, -1);
            if (';' !== $lastc && '}' !== $lastc) {
                return false;
            }
        } else {
            $semicolon = strpos($data, ';');
            $brace = strpos($data, '}');
            // Either ; or } must exist.
            if (false === $semicolon && false === $brace) {
                return false;
            }
            // But neither must be in the first X characters.
            if (false !== $semicolon && $semicolon < 3) {
                return false;
            }
            if (false !== $brace && $brace < 4) {
                return false;
            }
        }
        $token = $data[0];

        switch ($token) {
            case 's':
                if ($strict) {
                    if ('"' !== substr($data, -2, 1)) {
                        return false;
                    }
                } elseif (false === strpos($data, '"')) {
                    return false;
                }
            // or else fall through
            // no break
            case 'a':
            case 'O':
                return (bool)preg_match("/^{$token}:[0-9]+:/s", $data);

            case 'b':
            case 'i':
            case 'd':
                $end = $strict ? '$' : '';

                return (bool)preg_match("/^{$token}:[0-9.E-]+;{$end}/", $data);
        }

        return false;
    }
}
