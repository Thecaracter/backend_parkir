<?php

namespace Database\Seeders;

use App\Models\Informasi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InformasiSeeder extends Seeder
{
    public function run(): void
    {
        // 10 Data dalam 1 jam terakhir
        Informasi::create([
            'jenis_kendaraan' => 'motor',
            'area' => 'A',
            'user_id' => 1,
            'kapasitas' => 50,
            'foto' => 'parkir/motor-1.jpg',
            'poin' => 100,
            'created_at' => Carbon::now()->subMinutes(15)
        ]);

        Informasi::create([
            'jenis_kendaraan' => 'mobil',
            'area' => 'B',
            'user_id' => 1,
            'kapasitas' => 30,
            'foto' => 'parkir/mobil-1.jpg',
            'poin' => 150,
            'created_at' => Carbon::now()->subMinutes(20)
        ]);

        Informasi::create([
            'jenis_kendaraan' => 'motor',
            'area' => 'C',
            'user_id' => 1,
            'kapasitas' => 45,
            'foto' => 'parkir/motor-2.jpg',
            'poin' => 80,
            'created_at' => Carbon::now()->subMinutes(25)
        ]);

        Informasi::create([
            'jenis_kendaraan' => 'mobil',
            'area' => 'D',
            'user_id' => 1,
            'kapasitas' => 25,
            'foto' => 'parkir/mobil-2.jpg',
            'poin' => 120,
            'created_at' => Carbon::now()->subMinutes(30)
        ]);

        Informasi::create([
            'jenis_kendaraan' => 'motor',
            'area' => 'E',
            'user_id' => 1,
            'kapasitas' => 60,
            'foto' => 'parkir/motor-3.jpg',
            'poin' => 90,
            'created_at' => Carbon::now()->subMinutes(35)
        ]);

        Informasi::create([
            'jenis_kendaraan' => 'mobil',
            'area' => 'F',
            'user_id' => 1,
            'kapasitas' => 35,
            'foto' => 'parkir/mobil-3.jpg',
            'poin' => 140,
            'created_at' => Carbon::now()->subMinutes(40)
        ]);

        Informasi::create([
            'jenis_kendaraan' => 'motor',
            'area' => 'G',
            'user_id' => 1,
            'kapasitas' => 55,
            'foto' => 'parkir/motor-4.jpg',
            'poin' => 110,
            'created_at' => Carbon::now()->subMinutes(45)
        ]);

        Informasi::create([
            'jenis_kendaraan' => 'mobil',
            'area' => 'A',
            'user_id' => 1,
            'kapasitas' => 40,
            'foto' => 'parkir/mobil-4.jpg',
            'poin' => 130,
            'created_at' => Carbon::now()->subMinutes(50)
        ]);

        Informasi::create([
            'jenis_kendaraan' => 'motor',
            'area' => 'B',
            'user_id' => 1,
            'kapasitas' => 65,
            'foto' => 'parkir/motor-5.jpg',
            'poin' => 95,
            'created_at' => Carbon::now()->subMinutes(55)
        ]);

        Informasi::create([
            'jenis_kendaraan' => 'mobil',
            'area' => 'C',
            'user_id' => 1,
            'kapasitas' => 45,
            'foto' => 'parkir/mobil-5.jpg',
            'poin' => 160,
            'created_at' => Carbon::now()->subMinutes(59)
        ]);

        // 5 Data diluar 1 jam terakhir
        Informasi::create([
            'jenis_kendaraan' => 'motor',
            'area' => 'D',
            'user_id' => 1,
            'kapasitas' => 70,
            'foto' => 'parkir/motor-6.jpg',
            'poin' => 85,
            'created_at' => Carbon::now()->subHours(2)
        ]);

        Informasi::create([
            'jenis_kendaraan' => 'mobil',
            'area' => 'E',
            'user_id' => 1,
            'kapasitas' => 50,
            'foto' => 'parkir/mobil-6.jpg',
            'poin' => 145,
            'created_at' => Carbon::now()->subHours(3)
        ]);

        Informasi::create([
            'jenis_kendaraan' => 'motor',
            'area' => 'F',
            'user_id' => 1,
            'kapasitas' => 75,
            'foto' => 'parkir/motor-7.jpg',
            'poin' => 105,
            'created_at' => Carbon::now()->subHours(4)
        ]);

        Informasi::create([
            'jenis_kendaraan' => 'mobil',
            'area' => 'G',
            'user_id' => 1,
            'kapasitas' => 55,
            'foto' => 'parkir/mobil-7.jpg',
            'poin' => 135,
            'created_at' => Carbon::now()->subHours(5)
        ]);

        Informasi::create([
            'jenis_kendaraan' => 'motor',
            'area' => 'A',
            'user_id' => 1,
            'kapasitas' => 80,
            'foto' => 'parkir/motor-8.jpg',
            'poin' => 115,
            'created_at' => Carbon::now()->subHours(6)
        ]);
    }
}