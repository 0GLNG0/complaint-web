<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ComplaintExport implements FromCollection, WithHeadings
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        return Complaint::whereBetween('date', [$this->from, $this->to])->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Kode Aduan', 'Nama', 'Nomor Saluran', 'Alamat',
            'Nomor Handphone','Tanggal', 'Jenis Aduan', 'Isi Aduan', 'Status',
            'created_at', 'update_at', 'is_read','kecamatan','kelurahan','keterangan admin','waktu konfirmasi','jenis pelapor'
        ];
    }
}

