<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'description',
    ];

    /**
     * Ambil satu setting berdasarkan key.
     */
    public static function getValue(
        string $key,
        $default = null
    ) {
        $setting = static::query()
            ->where('key', $key)
            ->first();

        return $setting
            ? $setting->value
            : $default;
    }

    /**
     * Simpan atau update setting.
     */
    public static function setValue(
        string $key,
        $value,
        string $group = 'general',
        ?string $description = null
    ): self {
        return static::updateOrCreate(
            [
                'key' => $key,
            ],
            [
                'value' => $value,
                'group' => $group,
                'description' => $description,
            ]
        );
    }
}
