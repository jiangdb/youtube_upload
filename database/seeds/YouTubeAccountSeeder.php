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
            'display_name' => '8sian',
            'account_name' => 'asp-8sian2',
            'ssk_key_filename' => 'Youtube_8sian',
        ], [
            'display_name' => 'smg',
            'account_name' => 'asp-kankannews2',
            'ssk_key_filename' => 'Youtube_smg',
        ], [
            'display_name' => 'zjstv',
            'account_name' => 'asp-zhejiang2',
            'ssk_key_filename' => 'Youtube_zjstv',
        ]);

        foreach ($account as $key => $value) {
            $count = YouTubeAccount::where('account_name', $value['account_name'])->count();
            if ($count < 1) {
                YouTubeAccount::create($value);
            }
        }
    }
}
