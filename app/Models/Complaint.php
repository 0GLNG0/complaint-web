<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable=[
        'kode_aduan','nama','nomor_saluran', 'alamat', 'nomor_handphone', 'date', 'jenis_aduan', 'isi_aduan', 'status','kelurahan','kecamatan','jenis_pelapor'
    ];
}
