<?php

namespace Tests\Unit\Services;

use App\Enums\VehicleTypeEnum;
use App\Services\BidCalculatorService;
use PHPUnit\Framework\TestCase;

class ServicesBidCalculatorServiceTest extends TestCase
{
    public static function basicBuyerFeeDataProvider(): array
    {
        // TODO: Customize edges case message here
        return [
            'common vehicle with fee under 10' => [99.99, VehicleTypeEnum::COMMON, 10.0],
            'common vehicle with fee egal to 10' => [100.0, VehicleTypeEnum::COMMON, 10.0],
            'common vehicle with fee egal to 50' => [500.0, VehicleTypeEnum::COMMON, 50.0],
            'common vehicle with fee over 50' => [500.01, VehicleTypeEnum::COMMON, 50.0],
            'common vehicle with fee over 50 2' => [1000.0, VehicleTypeEnum::COMMON, 50.0],
            'common vehicle with fee between 10 and 50' => [398.0, VehicleTypeEnum::COMMON, 39.8],
            'common vehicle at 501' => [501.0, VehicleTypeEnum::COMMON, 50.0],
            'common vehicle at 1100' => [1100.0, VehicleTypeEnum::COMMON, 50.0],
            'luxury vehicle with fee under 25' => [249.99, VehicleTypeEnum::LUXURY, 25.0],
            'luxury vehicle with fee egal to 25' => [250.00, VehicleTypeEnum::LUXURY, 25.0],
            'luxury vehicle with fee between 25 and 200' => [1800.0, VehicleTypeEnum::LUXURY, 180.0],
            'luxury vehicle with fee egal to 200' => [2000.0, VehicleTypeEnum::LUXURY, 200.0],
            'luxury vehicle with fee just over 200' => [2000.01, VehicleTypeEnum::LUXURY, 200.0],
            'luxury vehicle with fee over 200' => [1000000.0, VehicleTypeEnum::LUXURY, 200.0],
        ];
    }

    /**
     * @dataProvider basicBuyerFeeDataProvider
     */
    public function test_calculate_basic_buyer_fee(float $price, VehicleTypeEnum $type, float $expectedFee): void
    {
        $ret = BidCalculatorService::calculate(vehiclePrice: $price, vehicleType: $type);

        $this->assertArrayHasKey('vehiclePrice', $ret);
        $this->assertSame($price, $ret['vehiclePrice']);
        $this->assertArrayHasKey('vehicleType', $ret);
        $this->assertSame($type, $ret['vehicleType']);
        $this->assertArrayHasKey('basicBuyerFee', $ret);
        $this->assertSame($expectedFee, $ret['basicBuyerFee']);
    }

    public static function specialFeeDataProvider(): array
    {
        // TODO: Customize edges case message here
        return [
            'common vehicle 2%' => [1000.0, VehicleTypeEnum::COMMON, 20.0],
            'luxury vehicle 4%' => [1000.0, VehicleTypeEnum::LUXURY, 40.0],
        ];
    }

    /**
     * @dataProvider specialFeeDataProvider
     */
    public function test_calculate_special_fee(float $price, VehicleTypeEnum $type, float $expectedFee): void
    {
        $ret = BidCalculatorService::calculate(vehiclePrice: $price, vehicleType: $type);

        $this->assertArrayHasKey('vehiclePrice', $ret);
        $this->assertSame($price, $ret['vehiclePrice']);
        $this->assertArrayHasKey('vehicleType', $ret);
        $this->assertSame($type, $ret['vehicleType']);
        $this->assertArrayHasKey('specialFee', $ret);
        $this->assertSame($expectedFee, $ret['specialFee']);
    }

    public static function associationFeeDataProvider(): array
    {
        return [
            'Low-value case' => [0.0, 0.0],
            'Lower bound' => [1.00, 5.00],
            'Just before upper bound' => [499.99, 5.00],
            'Upper bound first range' => [500.00, 5.00],
            'Transition to second range' => [500.01, 10.00],
            'Upper bound second range' => [1000.00, 10.00],
            'Transition to third range' => [1000.01, 15.00],
            'Just before upper bound' => [2999.99, 15.00],
            'Upper bound third range' => [3000.00, 15.00],
            'Transition to fourth range' => [3000.01, 20.00],
            'High-value case' => [1000000.00, 20.00],
        ];
    }

    /**
     * @dataProvider associationFeeDataProvider
     */
    public function test_calculate_association_fee(float $price, float $expectedFee): void
    {
        $ret = BidCalculatorService::calculate(vehiclePrice: $price, vehicleType: VehicleTypeEnum::COMMON);

        $this->assertArrayHasKey('vehiclePrice', $ret);
        $this->assertSame($price, $ret['vehiclePrice']);
        $this->assertArrayHasKey('vehicleType', $ret);
        $this->assertSame(VehicleTypeEnum::COMMON, $ret['vehicleType']);
        $this->assertArrayHasKey('associationFee', $ret);
        $this->assertSame($expectedFee, $ret['associationFee']);
    }

    public static function storageFeeDataProvider(): array
    {
        return [
            'Common vehicle price 0$' => [0.0, VehicleTypeEnum::COMMON, 100.0],
            'Common vehicle 398$' => [398.0, VehicleTypeEnum::COMMON, 100.0],
            'Common vehicle 501$' => [501.0, VehicleTypeEnum::COMMON, 100.0],
            'Common vehicle 58$' => [57.0, VehicleTypeEnum::COMMON, 100.0],
            'Common vehicle 1800$' => [1800.0, VehicleTypeEnum::COMMON, 100.0],
            'Luxury vehicle 1100$' => [1100.0, VehicleTypeEnum::LUXURY, 100.0],
            'Luxury vehicle 1000000$' => [1000000.0, VehicleTypeEnum::LUXURY, 100.0],
        ];
    }

    /**
     * @dataProvider storageFeeDataProvider
     */
    public function test_calculate_storage_fee(float $price, VehicleTypeEnum $vehicleType, float $expectedFee): void
    {
        $ret = BidCalculatorService::calculate(vehiclePrice: $price, vehicleType: $vehicleType);

        $this->assertArrayHasKey('vehiclePrice', $ret);
        $this->assertSame($price, $ret['vehiclePrice']);
        $this->assertArrayHasKey('vehicleType', $ret);
        $this->assertSame($vehicleType, $ret['vehicleType']);
        $this->assertArrayHasKey('storageFee', $ret);
        $this->assertSame($expectedFee, $ret['storageFee']);
    }

    public static function calculateTotalDataProvider(): array
    {
        return [
            'Common vehicle price 1000$' => [1000.0, VehicleTypeEnum::COMMON, 50.0, 20.0, 10.0, 100.0, 1180.0],
            'Common vehicle price 398$' => [398.0, VehicleTypeEnum::COMMON, 39.8, 7.96, 5.0, 100.0, 550.76],
            'Common vehicle price 501$' => [501.0, VehicleTypeEnum::COMMON, 50.0, 10.02, 10.0, 100.0, 671.02],
            'Common vehicle price 57$' => [57.0, VehicleTypeEnum::COMMON, 10.0, 1.14, 5.0, 100.0, 173.14],
            'Luxury vehicle price 1800$' => [1800.0, VehicleTypeEnum::LUXURY, 180.0, 72.0, 15.0, 100.0, 2167.0],
            'Common vehicle price 1100$' => [1100.0, VehicleTypeEnum::COMMON, 50.0, 22.0, 15.0, 100.0, 1287.0],
            'Luxury vehicle 1000000$' => [1000000.0, VehicleTypeEnum::LUXURY, 200.0, 40000.0, 20.0, 100.0, 1040320.0],
        ];
    }

    /**
     * @dataProvider calculateTotalDataProvider
     */
    public function test_calculate_total(
        float $price,
        VehicleTypeEnum $vehicleType,
        float $basicBuyerFee,
        float $specialFee,
        float $associationFee,
        float $storageFee,
        float $total
    ): void {
        $ret = BidCalculatorService::calculate(vehiclePrice: $price, vehicleType: $vehicleType);

        $this->assertArrayHasKey('vehiclePrice', $ret);
        $this->assertSame($price, $ret['vehiclePrice']);
        $this->assertArrayHasKey('vehicleType', $ret);
        $this->assertSame($vehicleType, $ret['vehicleType']);
        $this->assertArrayHasKey('basicBuyerFee', $ret);
        $this->assertSame($basicBuyerFee, $ret['basicBuyerFee']);
        $this->assertArrayHasKey('specialFee', $ret);
        $this->assertSame($specialFee, $ret['specialFee']);
        $this->assertArrayHasKey('associationFee', $ret);
        $this->assertSame($associationFee, $ret['associationFee']);
        $this->assertArrayHasKey('storageFee', $ret);
        $this->assertSame($storageFee, $ret['storageFee']);
        $this->assertArrayHasKey('total', $ret);
        $this->assertSame($total, $ret['total']);
    }
}
