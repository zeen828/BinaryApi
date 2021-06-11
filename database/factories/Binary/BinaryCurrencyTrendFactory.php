<?php

namespace Database\Factories\Binary;

use App\Models\Binary\BinaryCurrencyTrend;
use Illuminate\Database\Eloquent\Factories\Factory;
//
use App\Models\Binary\BinaryCurrency;
// 時間
use Carbon\Carbon;

class BinaryCurrencyTrendFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BinaryCurrencyTrend::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // 取得幣種
        $currency = $this->faker->randomElement(BinaryCurrency::all());
        // 開獎號碼
        $draw = $this->faker->randomNumber(2);
        // 走勢
        $trend = $this->faker->numberBetween(4000, 6000);
        // 開獎時間
        // $at = $this->faker->dateTimeBetween('-3 month', '1 month');
        $at = $this->faker->dateTimeBetween('-7 day', 'now');
        $drawAt = $at->format('Y-m-d h:i:00');
        $betAt = Carbon::parse($drawAt)->subMinute(1)->toDateTimeString();
        $stopAt = Carbon::parse($drawAt)->subSeconds(20)->toDateTimeString();
        return [
            // 'id' => '1',
            'binary_currency_id' => $currency->id,
            'period' => sprintf('%s%s', $currency->binary_code, $at->format('Ymdhi')),
            // 時間
            'bet_at' => $betAt,
            'stop_at' => $stopAt,
            'draw_at' => $drawAt,
            // 走勢
            'draw' => $draw,
            'trend_before' => sprintf('%s.%s', $trend, $draw),
            'trend_api' => sprintf('%s.%s', $trend, $draw),
            'trend' => sprintf('%s.%s', $trend, $draw),
            // 'forecast' => '[]',
            // 'forecast' => json_encode($forecast),
            // 'forecast' => $this->__forecast('5436.81', '01', '07'),
            'draw_rule_json' => '[]',
            // 統計(智能開獎用)
            'max' => $this->faker->randomNumber(2),
            'min' => $this->faker->randomNumber(2),
            'odd' => $this->faker->randomNumber(2),
            'even' => $this->faker->randomNumber(2),
            'rise' => $this->faker->randomNumber(2),
            'fall' => $this->faker->randomNumber(2),
            // 統計(報表用)
            'bet_quantity' => $this->faker->randomNumber(2),
            'bet_amount' => $this->faker->randomFloat(2, 0, 1000),
            'draw_quantity' => $this->faker->randomNumber(2),
            'draw_amount' => $this->faker->randomNumber(2),
            'draw_rate' => $this->faker->randomFloat(2, 0, 1),
            'redeem' => '0',
            // 狀態
            'status' => '1',
            // 'created_at' => $now_date,
            // 'updated_at' => $now_date,
        ];
    }
}
