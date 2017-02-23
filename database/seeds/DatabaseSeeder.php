<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $faker = Faker::create();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Model::unguard();


        App\User::truncate();
        factory(App\User::class)->create([
            'name'=>'asnike',
            'email'=>'asnike@notefolio.net',
            'password'=>bcrypt('111111'),
            'is_admin'=>1,
        ]);
        factory(App\User::class)->create([
            'name'=>'ruudnike',
            'email'=>'ruudnike@gmail.com',
            'password'=>bcrypt('111111'),
            'is_admin'=>0,
        ]);
        factory(App\User::class, 20)->create();
        $this->command->info('users table seeded');


        App\RealEstate::truncate();
        factory(App\RealEstate::class)->create([
            'name'=>'3호기',
            'lng'=>'126.7582496252',
            'lat'=>'37.5620466169',
            'address'=>'인천시 계양구 동양동 614',
            'sigungu'=>'인천시 계양구 동양동',
            'main_no'=>'614',
            'sub_no'=>'0',
            'building_name'=>'힐타운',
            'new_address'=>'당미길 32',
            'sigungu_code'=>'28260',
            'user_id'=>1,
            'own'=>1,
        ]);
        $this->command->info('realestates table seeded');

        App\PriceTag::truncate();
        factory(App\PriceTag::class)->create([
            'lng'=>'126.7582496252',
            'lat'=>'37.5620466169',
            'sigungu'=>'인천시 계양구 동양동',
            'main_no'=>'614',
            'sub_no'=>'0',
            'building_name'=>'힐타운',
            'new_address'=>'당미길 32',
            'reported_at'=>'2017-02-01',
            'exclusive_size'=>'33',
            'price'=>12796,
            'deposit'=>2000,
            'rental_cost'=>50,
            'floor'=>2,
        ]);
        factory(App\PriceTag::class)->create([
            'lng'=>'126.7582496252',
            'lat'=>'37.5620466169',
            'sigungu'=>'인천시 계양구 동양동',
            'main_no'=>'614',
            'sub_no'=>'0',
            'building_name'=>'힐타운',
            'new_address'=>'당미길 32',
            'reported_at'=>'2017-02-07',
            'exclusive_size'=>'33',
            'price'=>13000,
            'deposit'=>2000,
            'rental_cost'=>50,
            'floor'=>4,
        ]);
        $this->command->info('PriceTag table seeded');

        App\EarningRate::truncate();
        factory(App\EarningRate::class)->create([
            'realestate_id'=>'1',
            'price'=>127960000,
            'deposit'=>20000000,
            'monthlyfee'=>500000,
            'rate'=>0,
            'tax'=>1407560,
        ]);
        $this->command->info('EarningRate table seeded');

        App\Loan::truncate();
        factory(App\Loan::class)->create([
            'realestate_id'=>'1',
            'amount'=>93000000,
            'interest_rate'=>2.98,
            'repay_commission'=>1.5,
            'unredeem_period'=>3,
            'repay_period'=>3,
            'repay_method_id'=>3,
        ]);
        $this->command->info('Loan table seeded');

















        App\RepayMethod::truncate();
        factory(App\RepayMethod::class)->create([
            'name'=>'원리금균등상환',
        ]);
        factory(App\RepayMethod::class)->create([
            'name'=>'원금균등상환',
        ]);
        factory(App\RepayMethod::class)->create([
            'name'=>'만기상환',
        ]);

        Model::reguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
