<?php

use Illuminate\Database\Seeder;
use App\YouTubeAccount;

class YouTubeAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account = [];
        array_push($account, [
            'account_name' => 'test',
            'ssk_key_filename' => 'test.key',
        ]);

        foreach ($account as $key => $value) {
            YouTubeAccount::create($value);
        }
    }
}
