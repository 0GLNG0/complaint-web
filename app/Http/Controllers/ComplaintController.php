<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Complaint;
use setasign\Fpdf\Fpdf\Fpdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Helpers\DbfMapping;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use RealRashid\SweetAlert\Facades\Alert;



class ComplaintController extends Controller
{
    public function test(){
        Alert::success('Sukses', 'Pengaduan Anda telah terkirim. Kode Aduan Anda: ');
        return redirect()->back();
    }
    public function create()
{
    $mapField = \App\Helpers\DbfMapping::complaintFields(); // Pastikan ini valid
    $data = [
        'Kec Kota' => ['Kel. Banjaran','Kel. Dandangan','Kel. Ngadirejo','Kel. Pakelan','Kel. Semampir','Kel. Pocanan','Kel. Balowerti','Kel. Ringin Anom','Kel. Kemasan','Kel. Jagalan','Kel. Setono Pande','Kel. Kampung Dalem','Kel. Rejomulyo','Kel. Ngronggo','Kel. Kaliombo','Kel. Manisrenggo','Kel. Setono Gedong'],
        'Kec Mojoroto' => ['Kel. Mojoroto','Kel. Sukorame','Kel. Pojok','Kel. Lirboyo','Kel. Bandar Lor','Kel. Bandar Kidul','Kel. Banjarmlati','Kel. Tamanan','Kel. Campurejo','Kel. Bujel','Kel. Ngampel','Kel. Gayam','Kel. Mrican','Kel. Dermo'],
        'Kec Pesantren' => ['Kel. Pesantren','Kel. Burengan','Kel. Pakunden','Kel. Bangsal','Kel. Tosaren','Kel. Banaran','Kel. Tinalan','Kel. Singonegaran','Kel. Jamsaren','Kel. Ngletih','Kel. Bawang','Kel. Ketami','Kel. Betet','Kel. Blabak','Kel.Tempurejo']
    ];

    $count = \App\Models\Complaint::whereDate('created_at', \Carbon\Carbon::today())->count();

    return view('complaint.create', compact('data', 'count'));
}
   public function store(Request $request)
{
    // Validasi dasar
    $rules = [
        'jenis_pelapor' => 'required|in:personal,umum',
        'nama' => 'required',
        'alamat' => 'required',
        'kecamatan' => 'required',
        'kelurahan' => 'required',
        'nomor_handphone' => 'required|numeric',
        'date' => 'required|date',
        'jenis_aduan' => 'required',
        'isi_aduan' => 'required',
    ];

    if ($request->jenis_pelapor === 'personal') {
        $rules['nomor_saluran'] = 'required';
    } else {
        $rules['nomor_saluran'] = 'nullable';
    }

    $validated = $request->validate($rules);

    // Buat kode aduan
    $now = Carbon::now();
    $bulan = $now->format('m');
    $tahun = $now->format('Y');

    $jumlahAduanBulanIni = Complaint::whereMonth('created_at', $bulan)
        ->whereYear('created_at', $tahun)
        ->count();

    $kode_aduan = 'ADU-' . $tahun . $bulan . '-' . str_pad($jumlahAduanBulanIni + 1, 4, '0', STR_PAD_LEFT);

    // Simpan ke MySQL
    $complaint = Complaint::create([
        'jenis_pelapor' => $request->jenis_pelapor,
        'nama' => $request->nama,
        'alamat' => $request->alamat,
        'kecamatan' => $request->kecamatan,
        'kelurahan' => $request->kelurahan,
        'nomor_handphone' => $request->nomor_handphone,
        'nomor_saluran' => $request->nomor_saluran,
        'jenis_aduan' => $request->jenis_aduan,
        'isi_aduan' => $request->isi_aduan,
        'date' => $request->date,
        'status' => 'Pending',
        'kode_aduan' => $kode_aduan,
    ]);
// dd(DbfMapping::complaintFields());

    //Mapping field Laravel ➜ DBF
    $mapField = DbfMapping::complaintFields();
    $data = [];

    foreach ($mapField as $mysql => $dbf) {
        $data[$dbf] = $complaint->$mysql;
    }


    Alert::success('Sukses', 'Pengaduan Anda telah terkirim. Kode Aduan Anda: ' . $kode_aduan);
    return redirect()->route('complaint.create');
}


    public function checkStatusForm(){
        return view('complaint.check-status');
    }
    
    public function checkStatus(Request $request)
    {
        $request->validate([
            'keyword' => 'required'
        ]);
    
        $keyword = $request->input('keyword');
    
        $complaints = Complaint::where('nomor_handphone', $keyword)
            ->orWhere('nomor_saluran', $keyword)
            ->orWhere('alamat', $keyword)
            ->orWhere('kecamatan', $keyword)
            ->orWhere('nama', $keyword)
            ->get();
    
        if ($complaints->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pengaduan ditemukan.');
        }
    
        return view('complaint.status-result', compact('complaints'));
    } 
    
    public function downloadResi($id)
    {
        require_once base_path('vendor/setasign/fpdf/fpdf.php'); // wajib!
    
        $complaint = Complaint::findOrFail($id);

        // $complaint->alamat = Crypt::decryptString($complaint->alamat);
        // $complaint->nomor_handphone = Crypt::decryptString($complaint->nomor_handphone);
    
        $pdf = new \FPDF();
        $pdf->AddPage('p','A5');
        // heading
        $judul="USAHAAN UMUM DAERAH AIR MINUM\nTIRTA DHAHA KOTA KEDIRI";
        $alamat="Jl. A. Yani No.2, Banjaran, Kec. Kota, Kota Kediri, Jawa Timur 64129";
        $pdf->SetFont('Courier', 'B', 14,);
        $pdf->MultiCell(0,5,$judul,'0','C');
        $pdf->SetFont('Courier',);
        $pdf->MultiCell(0, 5,$alamat,'B','C');
        $pdf->Ln(2);
        $pdf->SetFont('Courier', 'B', 14 );
        $pdf->Cell(0, 10, 'RESI PENGADUAN', '', 1, 'C');
        $pdf->SetFont('Courier', '', 12);
        // isi/content
        function addRowWithBorder($pdf, $label, $value)
        {
            $pdf->Cell(40, 10, $label, 0);
            $pdf->Cell(5, 10, ':', 0, 0, 'C');
            $pdf->MultiCell(0, 10, $value, 0);
        }

    addRowWithBorder($pdf, 'Nama', $complaint->nama);
    addRowWithBorder($pdf, 'No Saluran', $complaint->nomor_saluran);
    addRowWithBorder($pdf, 'Alamat', $complaint->alamat);
    addRowWithBorder($pdf, 'kelurahan', $complaint->kelurahan);
    addRowWithBorder($pdf, 'kecamatan', $complaint->kecamatan);
    addRowWithBorder($pdf, 'Tanggal Aduan', $complaint->date);
    addRowWithBorder($pdf, 'Nomor Handphone', $complaint->nomor_handphone);
    addRowWithBorder($pdf, 'Jenis Aduan', $complaint->jenis_aduan);
    addRowWithBorder($pdf, 'Isi Aduan', $complaint->isi_aduan);
    addRowWithBorder($pdf, 'Status', $complaint->status);

    $pdf->Cell(10, 10, 'Pengadu', 0, 2,'L');
        $pdf->Ln(2);
        $pdf->Cell(10, 10, $complaint->nama, 0, 1,'L');

        ob_end_clean();

    // Output 
    $pdf->Output('I', 'resi_aduan_' . $complaint->nama . '.pdf');
    exit;
    }
    

}