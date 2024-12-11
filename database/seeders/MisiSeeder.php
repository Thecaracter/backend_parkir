<?php

namespace Database\Seeders;

use App\Models\Misi;
use App\Models\User;
use Illuminate\Database\Seeder;

class MisiSeeder extends Seeder
{
    public function run()
    {

        $users = User::all();

        foreach ($users as $user) {

            Misi::create([
                'user_id' => $user->id,
                'tipe' => 'pertama',
                'jumlah' => 0,
                'selesai' => false,
            ]);


            Misi::create([
                'user_id' => $user->id,
                'tipe' => 'harian',
                'jumlah' => 0,
                'selesai' => false,
            ]);


            Misi::create([
                'user_id' => $user->id,
                'tipe' => 'mingguan',
                'jumlah' => 0,
                'selesai' => false,
            ]);
        }
    }
}