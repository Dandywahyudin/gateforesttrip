<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum PaketTripKategori: string
{
    case Camping = 'camping';
    case Hiking = 'hiking';
    case Pendakian = 'pendakian';
    case Fotografi = 'fotografi';
    case Eksplorasi = 'eksplorasi';
    case Pantai = 'pantai';
    case Petualangan = 'petualangan';

    public function label(): string
    {
        return match ($this) {
            self::Camping => 'Camping',
            self::Hiking => 'Hiking',
            self::Pendakian => 'Pendakian',
            self::Fotografi => 'Fotografi',
            self::Eksplorasi => 'Eksplorasi',
            self::Pantai => 'Pantai',
            self::Petualangan => 'Petualangan',
        };
    }

    public static function options(): array
    {
        return array_reduce(self::cases(), function (array $options, self $case): array {
            $options[$case->value] = $case->label();

            return $options;
        }, []);
    }

    public static function values(): array
    {
        return array_map(static fn (self $case): string => $case->value, self::cases());
    }

    public static function labelFor(?string $kategori): ?string
    {
        return self::normalize($kategori)?->label() ?? ($kategori ? Str::headline($kategori) : null);
    }

    public static function valueFor(?string $kategori): ?string
    {
        return self::normalize($kategori)?->value;
    }

    public static function normalize(?string $kategori): ?self
    {
        if (! $kategori) {
            return null;
        }

        return self::tryFrom($kategori) ?? self::tryFrom(self::legacyMap()[$kategori] ?? '');
    }

    private static function legacyMap(): array
    {
        return [
            'Camping & Hiking' => self::Camping->value,
            'Camping' => self::Camping->value,
            'Hiking' => self::Hiking->value,
            'Pendakian' => self::Pendakian->value,
            'Fotografi' => self::Fotografi->value,
            'Adventure' => self::Petualangan->value,
            'Eksplorasi' => self::Eksplorasi->value,
            'Pantai' => self::Pantai->value,
        ];
    }
}