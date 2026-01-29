<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Models\Complaint;
use setasign\Fpdf\Fpdf\Fpdf;
use App\Exports\ComplaintExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use APP\Helpers\DbfMapping;


class AdminController extends Controller
{
    public function index(Request $request)
{
    $kategori_aduan =[
        'Pelayanan' => ['meter kotor/ berembun','pemeriksaan inst. & meter air','pemutusan instalasi','pemasangan instalasi air','pemasangan air baru','pemutusan sementara','ganti meter','dispensasi pejabat','baca ulang','putus sementara Krn Meter hilang','meter segel'],
        'Teknis' => ['meter rusak','stand meter tertukar','persil bocor','stop keran bocor','air kecil/tidak mengalir','air kotor','meter hilang','box terkunci','box terkunci(tafsir)','rumah kosong','rumah kosong(tafsiir)','rumah tutup','rumah tutup(tafsir)','stang meter mundur','stand meter jalan terus','kran bocor/rusak','meter terjept','kopling putus/bocor'],
        'Rekening Air' => ['stand meter tetap','pakai rata-rata','stand melonjak','angka sesuai dipapan','koreksi stand meter','stand melonjak','stand meter diatur'],
        'Lainnya' => ['lain-lain']
    ];

    $query = Complaint::query();

    if ($request->filled('kategori') && isset($kategori_aduan[$request->kategori])) {
        $query->whereIn('jenis_aduan', $kategori_aduan[$request->kategori]);
    }

    if ($request->filled('jenis_aduan')) {
        $query->where('jenis_aduan', $request->jenis_aduan);
    }

    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('nama', 'like', '%' . $request->search . '%')
              ->orWhere('nomor_handphone', 'like', '%' . $request->search . '%')
              ->orWhere('nomor_saluran', 'like', '%' . $request->search . '%')
              ->orWhere('alamat', 'like', '%' . $request->search . '%')
              ->orWhere('kode_aduan', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('tanggal')) {
        $query->whereDate('date', $request->tanggal);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    
    $kategori = $request->input('kategori');

    $kategori_aduan = [
        'Pelayanan' => ['meter kotor/ berembun','pemeriksaan inst. & meter air','pemutusan instalasi','pemasangan instalasi air','pemasangan instalasasi air baru','pemutusan sementara','ganti meter','dispensasi pejabat','baca ulang','putus sementara Krn Meter hilang','meter segel'],
        'Teknis' => ['meter rusak','stand meter tertukar','persil bocor','stop keran bocor','air kecil/tidak mengalir','air kotor','meter hilang','box terkunci','box terkunci(tafsir)','rumah kosong','rumah kosong(tafsiir)','rumah tutup','rumah tutup(tafsir)','stang meter mundur','stand meter jalan terus','kran bocor/rusak','meter terjept','kopling putus/bocor'],
        'Rekening Air' => ['stand meter tetap','pakai rata-rata','stand melonjak','angka sesuai dipapan','koreksi stand meter','stand melonjak','stand meter diatur'],
        'Lainnya' => ['lain-lain']
    ];

    $complaints = Complaint::query();

    if ($kategori && isset($kategori_aduan[$kategori])) {
        $complaints->whereIn('jenis_aduan', $kategori_aduan[$kategori]);
    }

    // Final query dengan sorting dan pagination
    $complaints = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

    return view('admin.index', compact('complaints','kategori_aduan'));
}

public function destroy($id)
{
    $complaint = Complaint::findOrFail($id);
    $complaint->delete();

    return redirect()->back()->with('success', 'Data pengaduan berhasil dihapus.');
}
    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);
        // $complaint->alamat = Crypt::decryptString($complaint->alamat);
        // $complaint->nomor_handphone = Crypt::decryptString($complaint->nomor_handphone);
        if (!$complaint->is_read) {
            $complaint->update(['is_read' => true]);
        }
        return view('admin.show', compact('complaint'));
    }

    public function validateComplaint(Request $request, $id)
{
    $request->validate([
        'keterangan_admin' => 'required|string|max:1000',
    ]);

    $complaint = Complaint::findOrFail($id);
    $complaint->status = 'selesai';
    $complaint->keterangan_admin = $request->keterangan_admin;
    $complaint->waktu_konfirmasi = now();
    $complaint->save();

    return redirect()->route('admin.filter')->with('success', 'Pengaduan telah dikonfirmasi selesai.');
}

  public function filter(Request $request)
{
    $kategori_aduan = [
        'Pelayanan' => ['meter kotor/ berembun','pemeriksaan inst. & meter air','pemutusan instalasi','pemasangan instalasi air','pemasangan instalasasi air baru','pemutusan sementara','ganti meter','dispensasi pejabat','baca ulang','putus sementara Krn Meter hilang','meter segel'],
        'Teknis' => ['meter rusak','stand meter tertukar','persil bocor','stop keran bocor','air kecil/tidak mengalir','air kotor','meter hilang','box terkunci','box terkunci(tafsir)','rumah kosong','rumah kosong(tafsiir)','rumah tutup','rumah tutup(tafsir)','stang meter mundur','stand meter jalan terus','kran bocor/rusak','meter terjept','kopling putus/bocor'],
        'Rekening Air' => ['stand meter tetap','pakai rata-rata','stand melonjak','angka sesuai dipapan','koreksi stand meter','stand melonjak','stand meter diatur'],
        'Lainnya' => ['lain-lain']
    ];

    $query = Complaint::query();

    // Filter pencarian umum
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('nama', 'like', '%' . $request->search . '%')
              ->orWhere('nomor_handphone', 'like', '%' . $request->search . '%')
              ->orWhere('nomor_saluran', 'like', '%' . $request->search . '%')
              ->orWhere('alamat', 'like','%'. $request->search . '%')
              ->orWhere('kode_aduan', 'like', '%' . $request->search . '%');
        });
    }

    // Filter status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter tanggal
    if ($request->filled('tanggal')) {
        $query->whereDate('date', $request->tanggal);
    }

    // Filter bulan & tahun
    if ($request->filled('bulan')) {
        $query->whereMonth('date', $request->bulan);
    }

    if ($request->filled('tahun')) {
        $query->whereYear('date', $request->tahun);
    }

    // Filter kategori
    $kategori = $request->input('kategori');
    if ($kategori && isset($kategori_aduan[$kategori])) {
        $query->whereIn('jenis_aduan', $kategori_aduan[$kategori]);
    }

    // Finalisasi query
    $complaints = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

    return view('admin.filter', compact('complaints', 'kategori', 'kategori_aduan'));
}


// app/Http/Controllers/ComplaintController.php

// Controller
public function edit($id) {
    $complaint = Complaint::findOrFail($id);
    return view('admin.edit',compact('complaint'));
}

public function update(Request $request, $id) {
    
    $validated = $request->validate([
        'nama' => 'required',
        'nomor_handphone' => 'required',
        'alamat' => 'required',
        'isi_aduan' => 'required',
    ]);

    $complaint = Complaint::findOrFail($id);
    $complaint->update($validated);

    return redirect()->route('admin.show', $complaint->id)
           ->with('success', 'Data berhasil diupdate');
}


public function exportByJenisAduan(Request $request)
{
    $tahun = $request->tahun ?? now()->year;
    
    // 1. Mapping kategori
    $kategoriMapping = [
        'Pelayanan' => [
            'meter kotor/ berembun','pemeriksaan inst. & meter air','pemutusan instalasi',
            'pemasangan instalasi air','pemasangan air baru','pemutusan sementara','ganti meter',
            'dispensasi pejabat','baca ulang','putus sementara Krn Meter hilang','meter segel',
        ],
        'Teknis' => [
            'meter rusak','stand meter tertukar','persil bocor','stop keran bocor',
            'air kecil/tidak mengalir','air kotor','meter hilang','box terkunci',
            'box terkunci(tafsir)','rumah kosong','rumah kosong(tafsiir)','rumah tutup',
            'rumah tutup(tafsir)','stang meter mundur','stand meter jalan terus',
            'kran bocor/rusak','meter terjept','kopling putus/bocor',
        ],
        'Rekening Air' => [
            'stand meter tetap','pakai rata-rata','stand melonjak','angka sesuai dipapan',
            'koreksi stand meter','stand meter diatur',
        ],
        'Lainnya' => ['lain-lain'],
    ];

    $subToKategori = [];
    foreach ($kategoriMapping as $kategori => $subs) {
        foreach ($subs as $sub) {
            $subToKategori[$sub] = $kategori;
        }
    }
    
    // 2. Ambil data dengan status
    $complaints = \App\Models\Complaint::selectRaw('MONTH(date) as bulan, jenis_aduan, status, COUNT(*) as jumlah')
        ->whereYear('date', $tahun)
        ->groupBy('bulan', 'jenis_aduan', 'status')
        ->orderBy('bulan')
        ->orderBy('jenis_aduan')
        ->get();

    // 3. Kelompokkan berdasarkan bulan dan kategori
    $grouped = [];

    foreach ($complaints as $c) {
        $bulan = $c->bulan;
        $jenisAduan = strtolower($c->jenis_aduan);

        // Tentukan kategori
        $kategori = 'Lainnya';
        foreach ($kategoriMapping as $key => $list) {
            if (in_array($jenisAduan, array_map('strtolower', $list))) {
                $kategori = $key;
                break;
            }
        }

        // Inisialisasi jika belum ada
        if (!isset($grouped[$bulan][$kategori])) {
            $grouped[$bulan][$kategori] = ['total' => 0, 'selesai' => 0, 'pending' => 0];
        }

        // Tambahkan jumlah
        $grouped[$bulan][$kategori]['total'] += $c->jumlah;
        if (strtolower($c->status) == 'selesai') {
            $grouped[$bulan][$kategori]['selesai'] += $c->jumlah;
        } else {
            $grouped[$bulan][$kategori]['pending'] += $c->jumlah;
        }
    }

    // 4. Generate PDF
    $pdf = new \FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Laporan Jumlah Pengaduan PDAM '.$tahun, 0, 1, 'C');
    $pdf->Ln(5);

    // Header
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(10, 10, 'No', 1);
    $pdf->Cell(30, 10, 'Bulan', 1);
    $pdf->Cell(60, 10, 'Kategori Aduan', 1);
    $pdf->Cell(20, 10, 'Masuk', 1);
    $pdf->Cell(20, 10, 'Selesai', 1);
    $pdf->Cell(20, 10, 'Pending', 1);
    $pdf->Ln();

    // Isi tabel
    $pdf->SetFont('Arial', '', 10);
    $no = 1;
    foreach ($grouped as $bulan => $kategoriItems) {
        $rowCount = count($kategoriItems);
        $first = true;

        foreach ($kategoriItems as $kategori => $data) {
            if ($first) {
                $pdf->Cell(10, 10 * $rowCount, $no++, 1, 0, 'C');
                $pdf->Cell(30, 10 * $rowCount, Carbon::create()->month($bulan)->translatedFormat('F'), 1, 0, 'C');
                $first = false;
            } else {
                $pdf->Cell(10, 10, '', 1);
                $pdf->Cell(30, 10, '', 1);
            }

            $pdf->Cell(60, 10, $kategori, 1);
            $pdf->Cell(20, 10, $data['total'], 1, 0, 'C');
            $pdf->Cell(20, 10, $data['selesai'], 1, 0, 'C');
            $pdf->Cell(20, 10, $data['pending'], 1, 0, 'C');
            $pdf->Ln();
        }
    }

    // Output PDF
    $pdf->Output();
    exit;
}


public function exportPDF(Request $request)
{
    $request->validate([
        'from' => 'required|date',
        'to' => 'required|date|after_or_equal:from'
    ]);

    $from = $request->from;
    $to = $request->to;

    $complaints = Complaint::whereBetween('date', [$from, $to])->get();

    $pdf = new \FPDF();
    $pdf->AddPage('L', 'A3');
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, "Data Pengaduan ($from s/d $to)", 0, 1, 'C');

    // Header
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 10, 'Kode Aduan', 1);
    $pdf->Cell(10, 10, 'ID', 1);
    $pdf->Cell(30, 10, 'Nama', 1);
    $pdf->Cell(30, 10, 'No. Saluran', 1);
    $pdf->Cell(30, 10, 'jenis_pelapor', 1);
    $pdf->Cell(60, 10, 'isi_aduan', 1);
    $pdf->Cell(40, 10, 'created_at', 1);
    $pdf->Cell(20, 10, 'Status', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);

    foreach ($complaints as $c) {
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $lineHeight = 6;
        $isiWidth = 60;

        // Estimasi tinggi isi_aduan
        $isiText = $c->isi_aduan;
        $nbLines = $pdf->GetStringWidth($isiText) / ($isiWidth - 2);
        $nbLines = ceil($nbLines);
        $maxHeight = max($lineHeight * $nbLines, 10);

        // Simpan posisi awal
        $x = $pdf->GetX();
        $y = $pdf->GetY();

        // Buat semua kolom manual, kecuali isi_aduan pakai MultiCell
        $pdf->Cell(40, $maxHeight, $c->kode_aduan, 1);
        $pdf->Cell(10, $maxHeight, $c->id, 1);
        $pdf->Cell(30, $maxHeight, $c->nama, 1);
        $pdf->Cell(30, $maxHeight, $c->nomor_saluran, 1);
        $pdf->Cell(30, $maxHeight, $c->jenis_pelapor, 1);

        // isi_aduan pakai MultiCell
        $pdf->SetXY($x + 140, $y); // geser X: 40+10+30+30+30 = 140
        $pdf->MultiCell($isiWidth, $lineHeight, $isiText, 1);

        // Geser ke kanan setelah MultiCell
        $pdf->SetXY($x + 200, $y);
        $pdf->Cell(40, $maxHeight, $c->created_at, 1);
        $pdf->Cell(20, $maxHeight, $c->status, 1);

        // Geser ke bawah untuk baris berikutnya
        $pdf->SetXY($x, $y + $maxHeight);
    }

    $pdf->Output();
    exit;
}

public function exportExcel(Request $request)
{
    $request->validate([
        'from' => 'required|date',
        'to' => 'required|date|after_or_equal:from'
    ]);

    return Excel::download(new ComplaintExport($request->from, $request->to), 'data-pengaduan.xlsx');
}



}

