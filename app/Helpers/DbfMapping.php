<?php

namespace App\Helpers;

class DbfMapping
{
    public static function complaintFields()
    {
        return [
            'NOREG' => 'kode_aduan',
        'NAMAL' => 'nama',
        'ALAMATL' => 'alamat',
        'TGLADU' => 'date',
        'STATUS' => 'status',
        'JADU' => 'created_at',
        'TINDAK' => 'keterangan_admin',
        'TGSELESAI' => 'waktu_konfirmasi',
        'HP' => 'nomor_handphone',
        'NOPER' => 'nomor_saluran',
        'LAIN1' => 'isi_aduan',
        'EDSELES' =>'keterangan_admin'
        ];
    }
}
