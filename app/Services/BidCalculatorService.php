<?php

namespace App\Services;

use App\Enums\VehicleTypeEnum;

class BidCalculatorService
{
    public static function calculate(float $vehiclePrice, VehicleTypeEnum $vehicleType): array
    {
        $basicBuyerFee = self::calculateBasicBuyerFee(vehiclePrice: $vehiclePrice, vehicleType: $vehicleType);
        $specialFee = self::calculateSpecialFee(vehiclePrice: $vehiclePrice, vehicleType: $vehicleType);
        $associationFee = self::calculateAssociationFee(vehiclePrice: $vehiclePrice);
        $storageFee = self::calculateStorageFee();

        // TODO: Improve response by returning back a BidCalculatorResponse ?
        return [
            'vehiclePrice' => $vehiclePrice,
            'vehicleType' => $vehicleType,
            'basicBuyerFee' => $basicBuyerFee,
            'specialFee' => $specialFee,
            'associationFee' => $associationFee,
            'storageFee' => $storageFee,
            'total' => round($vehiclePrice + $basicBuyerFee + $specialFee + $associationFee + $storageFee, 2),
        ];
    }

    protected static function calculateBasicBuyerFee(float $vehiclePrice, VehicleTypeEnum $vehicleType): float
    {
        $fee = $vehiclePrice * 0.1;

        $fee = match ($vehicleType) {
            VehicleTypeEnum::COMMON => min(50.0, max(10.0, $fee)),
            VehicleTypeEnum::LUXURY => min(200.0, max(25.0, $fee)),
        };

        return round($fee, 2);
    }

    protected static function calculateSpecialFee(float $vehiclePrice, VehicleTypeEnum $vehicleType): float
    {
        $fee = match ($vehicleType) {
            VehicleTypeEnum::COMMON => $vehiclePrice * 0.02,
            VehicleTypeEnum::LUXURY => $vehiclePrice * 0.04,
        };

        return round($fee, 2);
    }

    protected static function calculateAssociationFee(float $vehiclePrice): float
    {
        $fee = match (true) {
            $vehiclePrice >= 1.0 && $vehiclePrice <= 500.0 => 5.0,
            $vehiclePrice > 500.0 && $vehiclePrice <= 1000.0 => 10.0,
            $vehiclePrice > 1000.0 && $vehiclePrice <= 3000.0 => 15.0,
            $vehiclePrice > 3000.0 => 20.0,
            default => 0.0, // Optional: Handle invalid cases
        };

        return round($fee, 2);
    }

    protected static function calculateStorageFee(): float
    {
        return 100.0;
    }
}
