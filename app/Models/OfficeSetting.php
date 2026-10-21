<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeSetting extends Model
{
    protected $fillable = [
        'clinic_name',
        'clinic_address',
        'map_url',
        'whatsapp_phone',
    ];

    public static function current(): self
    {
        return self::query()->firstOrCreate(['id' => 1]);
    }

    public function getWhatsappDigitsAttribute(): string
    {
        return preg_replace('/\D+/', '', $this->whatsapp_phone ?? '');
    }

    public function getMapLinkAttribute(): ?string
    {
        if ($this->map_url) {
            return $this->map_url;
        }

        if ($this->clinic_address) {
            return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($this->clinic_address);
        }

        return null;
    }

    public function getMapEmbedLinkAttribute(): ?string
    {
        if (!$this->clinic_address) {
            return null;
        }

        return 'https://maps.google.com/maps?q=' . urlencode($this->clinic_address) . '&output=embed';
    }
}