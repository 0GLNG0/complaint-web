<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Complaint;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Andi',
                'nomor_handphone' => '08123456789',
                'nomor_saluran' => '12345',
                'alamat' => 'Jl. Merdeka 1',
                'kecamatan' => 'Kecamatan A',
                'kelurahan' => 'Kelurahan 1',
                'jenis_aduan' => 'Air Mati',
                'isi_aduan' => 'Air mati dari pagi',
                'status' => 'Pending',
                'date' => now(),
            ],
            [
                'nama' => 'Budi',
                'nomor_handphone' => '08234567890',
                'nomor_saluran' => '67890',
                'alamat' => 'Jl. Merdeka 2',
                'kecamatan' => 'Kecamatan B',
                'kelurahan' => 'Kelurahan 3',
                'jenis_aduan' => 'Pipa Bocor',
                'isi_aduan' => 'Ada kebocoran di saluran utama',
                'status' => 'Selesai',
                'date' => now(),
            ]
        ];

        foreach ($data as $item) {
            Complaint::create($item);
        }
    }
}
