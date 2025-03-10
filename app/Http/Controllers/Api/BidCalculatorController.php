<?php

namespace App\Http\Controllers\Api;

use App\Enums\VehicleTypeEnum;
use App\Http\Controllers\Controller;
use App\Services\BidCalculatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BidCalculatorController extends Controller
{
    //
    public function calculate(Request $request): JsonResponse
    {
        // TODO: Add custom request

        $request->validate([
            'vehicle_price' => 'required|numeric|min:1|max:5000000',
            'vehicle_type' => 'required', // TODO: Validation avec l'enum
        ]);

        $vehiclePrice = $request->input('vehicle_price');
        $vehicleType = $request->input('vehicle_type');

        // Calculate the bid
        $result = BidCalculatorService::calculate(
            vehiclePrice: $vehiclePrice,
            vehicleType: VehicleTypeEnum::tryFrom($vehicleType)
        );

        return response()->json([
            'success' => true,
            'vehicle_price' => $result['vehiclePrice'],
            'vehicle_type' => $result['vehicleType']->label(),
            'basic_buyer_fee' => $result['basicBuyerFee'],
            'special_fee' => $result['specialFee'],
            'association_fee' => $result['associationFee'],
            'storage_fee' => $result['storageFee'],
            'total' => $result['total'],
        ]);
    }
}
