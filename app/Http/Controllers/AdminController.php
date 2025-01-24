<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pinjaman;
use App\Models\Tanggungan;
use Illuminate\Http\Request;
use App\Models\SimpananPokok;
use App\Exports\ExportAnggota;
use App\Models\TransaksiPokok;
use App\Exports\ExportTanggungan;
use App\Models\TransaksiPinjaman;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportTransaksiPinjaman;
use App\Exports\ExportTransaksiSimpanan;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('usertype', 'user')->count();
        $totalBesarPinjaman = Pinjaman::where('keterangan', 'Disetujui')->sum('besar_pinjaman');
        $totalSimpanan = SimpananPokok::sum('total_simpanan');

        $totalKeseluruhan = $totalBesarPinjaman + $totalSimpanan;

        $data = [
            'title' => 'Dashboard',
        ];
        return view('roleAdmin.dashboard', $data, compact('totalUsers', 'totalKeseluruhan'));
    }

    public function getChartData()
    {
        // Fetch total pinjaman per month for 'lunas' records
        $totalPinjaman = DB::table('pinjaman')
        ->select(DB::raw('MONTH(tgl_pengajuan) as month'), DB::raw('SUM(besar_pinjaman) as total'))
        ->where('keterangan', 'Disetujui')
        ->groupBy(DB::raw('MONTH(tgl_pengajuan)'))
        ->pluck('total', 'month');

        // Fetch total simpanan pokok per month
        $totalSimpanan = DB::table('simpanan_pokoks')
        ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total_simpanan) as total'))
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->pluck('total', 'month');

        // Initialize arrays with 0 for each month
        $pinjamanData = array_fill(1, 12, 0);
        $simpananData = array_fill(1, 12, 0);

        // Populate arrays with data from queries
        foreach ($totalPinjaman as $month => $total) {
            $pinjamanData[$month] = $total;
        }

        foreach ($totalSimpanan as $month => $total) {
            $simpananData[$month] = $total;
        }

        return response()->json([
            'pinjaman' => array_values($pinjamanData),
            'simpanan' => array_values($simpananData)
        ]);
    }

    public function dataAnggota()
    {
        $users = User::where('usertype', 'user')->orderBy('id_user', 'asc')->get();
        $data = [
            'title' => 'Data Anggota',
        ];
        return view('roleAdmin.dataAnggota', $data, compact('users'));
    }

    public function editAnggota($id_user)
    {
        $user = User::findOrFail($id_user);
        return response()->json($user);
    }

    public function updateUser(Request $request, $id_user)
    {
        $user = User::findOrFail($id_user);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id_user . ',id_user',
            'NIP' => 'required|digits_between:1,17|max:255|unique:users,NIP,' . $user->id_user . ',id_user',
            'jenis_kelamin' => 'required|string',
            'alamat' => 'required|string|max:255',
            'no_tlp' => 'required|digits_between:1,15',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'email.required' => 'Email wajib diisi, wajib menggunakan @.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',

            'NIP.required' => 'NIP wajib diisi.',
            'NIP.digits_between' => 'NIP wajib diisi dengan angka dan maksimal 17 angka.',
            'NIP.unique' => 'NIP sudah terdaftar.',

            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi.',
            'jenis_kelamin.string' => 'Jenis kelamin harus berupa teks.',

            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter.',

            'no_tlp.required' => 'Nomor telepon wajib diisi.',
            'no_tlp.digits_between' => 'Nomor telepon wajib diisi dengan angka dan tanpa spasi.',
        ]);

        try {
            $user->update($request->all());
            return response()->json(['success' => true, 'message' => 'Data anggota berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function dataSimpananPokok()
    {
        $simpanan = SimpananPokok::orderBy('id_simpanan_pokok', 'asc')->get();
        $data = [
            'title' => 'Data Simpanan Pokok',
        ];
        return view('roleAdmin.dataSimpananPokok', $data, compact('simpanan'));
    }

    public function dataPinjaman()
    {
        $pinjaman = Pinjaman::orderBy('id_pinjaman', 'asc')->get();
        $data = [
            'title' => 'Data Pinjaman',
        ];
        return view('roleAdmin.dataPinjaman', $data, compact('pinjaman'));
    }

    public function buatTransaksiSimpanan(Request $request)
    {
        $simpananList = SimpananPokok::where('status_simpanan', 'Lunas')->get();

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');

        foreach ($simpananList as $simpanan) {
            $params = [
                'transaction_details' => [
                    'order_id' => rand(),
                    'gross_amount' => $simpanan->iuran,
                ],
                'customer_details' => [
                    'first_name' => $simpanan->user->nama,
                    'email' => $simpanan->user->email,
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Set jatuh_tempo to the first day of the next month ->startOfMonth()
            $now = now();
            $jatuh_tempo = $now->copy()->addMonth();

            TransaksiPokok::create([
                'id_simpanan_pokok' => $simpanan->id_simpanan_pokok,
                'jatuh_tempo' => $jatuh_tempo,
                'snap_token' => $snapToken,
                'keterangan' => 'Belum Lunas',
            ]);

            $simpanan->status_simpanan = 'Belum Lunas';
            $simpanan->save();
        }

        return response()->json(['message' => 'Transaksi berhasil dibuat untuk pengguna dengan status Lunas']);
    }

    // Fungsi untuk memeriksa status simpanan
    public function checkSimpananStatus()
    {
        $simpananList = SimpananPokok::all();
        $disableButton = true;

        // Periksa apakah ada pengguna yang berstatus Lunas
        foreach ($simpananList as $simpanan) {
            if ($simpanan->status_simpanan === 'Lunas') {
                $disableButton = false;
                break;
            }
        }

        return response()->json(['disableButton' => $disableButton]);
    }

    protected function createTanggungan($pinjaman)
    {
        $besar_pinjaman = $pinjaman->besar_pinjaman;
        // Bunga tahunan default
        $bunga_bulanan = 0.08;
        // Jumlah cicilan
        $tenor = $pinjaman->tenor_pinjaman;
        //jumlah bunga
        $jumlah_bunga = $besar_pinjaman * 0.08;
        //total pinjaman
        $total_pembayaran = $besar_pinjaman + ($bunga_bulanan * $besar_pinjaman);
        //pembayaran bulanan
        $pembayaran_bulanan = $total_pembayaran / $tenor;

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');

        // Pembayaran Lunas
        $paramsLunas = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => ceil($besar_pinjaman),
            ),
            'customer_details' => array(
                'first_name' => $pinjaman->user->nama,
                'email' => $pinjaman->user->email,
            ),
        );
        $snapTokenLunas = \Midtrans\Snap::getSnapToken($paramsLunas);

         // Buat data tanggungan
        $tanggungan = Tanggungan::create([
            'id_pinjaman' => $pinjaman->id_pinjaman,
            'total_pinjaman' => $total_pembayaran,
            'bunga_pinjaman' => $jumlah_bunga,
            'iuran_perBulan' => $pembayaran_bulanan,
            'sisa_pinjaman' => $total_pembayaran,
            'sisa_tenor' => $pinjaman->tenor_pinjaman,
            'status_pinjaman' => 'Belum Lunas',
            'snap_tokenLunas' => $snapTokenLunas,
        ]);

        // Pastikan tanggungan berhasil dibuat
        if ($tanggungan) {
            $jatuh_tempo_awal = now()->endOfDay();
            for ($i = 1; $i <= $tenor; $i++) {
                $params = array(
                    'transaction_details' => array(
                        'order_id' => rand(),
                        'gross_amount' => ceil($pembayaran_bulanan),
                    ),
                    'customer_details' => array(
                        'first_name' => $tanggungan->pinjaman->user->nama,
                        'email' => $tanggungan->pinjaman->user->email,
                    ),
                );
                $snapToken = \Midtrans\Snap::getSnapToken($params);

                $jatuh_tempo = $jatuh_tempo_awal->copy()->addMonths($i);
                TransaksiPinjaman::create([
                    'id_tanggungan' => $tanggungan->id_tanggungan,
                    'jatuh_tempo' => $jatuh_tempo,
                    'tanggal_pembayaran' => null,
                    'snap_token' => $snapToken,
                    'keterangan' => 'Bayar cicilan ke-' . $i
                ]);
            }
        }
    }

    public function updatePinjamanStatus(Request $request, $id_pinjaman)
    {
        DB::beginTransaction();

        try {
            $pinjaman = Pinjaman::findOrFail($id_pinjaman);
            $pinjaman->keterangan = $request->input('status');
            $pinjaman->save();

            if ($pinjaman->keterangan == 'Disetujui') {
                $this->createTanggungan($pinjaman);
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Error updating status', 'error' => $e->getMessage()], 500);
        }
    }

    public function dataTanggungan()
    {
        $tanggungan = Tanggungan::with('pinjaman.user')->whereHas('pinjaman', function ($query) {
            $query->where('keterangan', 'Disetujui');
        })->get();

        $data = [
            'title' => 'Data Tanggungan',
        ];

        return view('roleAdmin.dataTanggungan', $data, compact('tanggungan'));
    }

    public function destroyUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['success' => 'Anggota berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus anggota.'], 500);
        }
    }

    public function viewTransaksiSimpanan(){
        $transaksiPokok = TransaksiPokok::orderBy('id_transaksiPokok', 'asc')->get();

        $data = [
            'title' => 'Transaksi Simpanan'
        ];

        return view('roleAdmin.transaksiSimpanan', $data, compact('transaksiPokok'));
    }

    public function viewTransaksiPinjaman(){
        $transaksiPinjaman = TransaksiPinjaman::orderBy('id_transaksiPinjaman', 'asc')->get();

        $data = [
            'title' => 'Transaksi Pinjaman'
        ];

        return view('roleAdmin.transaksiPinjaman', $data, compact('transaksiPinjaman'));
    }

    public function exportExcelAnggota(){
        return Excel::download(new ExportAnggota, 'DataAnggota.xlsx');
    }

    public function exportExcelTransaksiSimpanan(){
        return Excel::download(new ExportTransaksiSimpanan, 'TransaksiSimpanan.xlsx');
    }

    public function exportExcelTanggungan()
    {
        return Excel::download(new ExportTanggungan, 'Tanggungan.xlsx');
    }

    public function exportExcelTransaksiPinjaman(){
        return Excel::download(new ExportTransaksiPinjaman, 'TransaksiPinjaman.xlsx');
    }
}
