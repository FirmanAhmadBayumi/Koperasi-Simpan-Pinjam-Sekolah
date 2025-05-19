<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pinjaman;
use App\Models\Tanggungan;
use Illuminate\Http\Request;
use App\Models\SimpananPokok;
use App\Models\TransaksiPokok;
use App\Models\TransaksiPinjaman;
use Illuminate\Support\Facades\DB;
use App\Models\KonfigurasiPinjaman;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function profilSekolah(){
        $data = [
            'title' => 'Kelola Profil Sekolah'
        ];

        return view('roleAdmin.profilSekolah', $data);
    }

    public function konfigurasiPinjaman(){
        $konfigurasiPinjaman = KonfigurasiPinjaman::first();

        $data = [
            'title' => 'Konfigurasi Pinjaman',
        ];
        return view('roleAdmin.konfigurasiPinjaman', $data, compact('konfigurasiPinjaman'));
    }

    public function updateKonfigurasiPinjaman(Request $request){
        $data = $request->validate([
            'bunga_pinjaman' => 'required|numeric|between:0.03,1.00|regex:/^\d+(\.\d{1,2})?$/',
            'maks_pinjaman' => 'required|numeric|min:500000',
            'maks_tenor' => 'required|numeric|min:3'
        ],[
            'bunga_pinjaman.required' => 'Bunga pinjaman wajib diisi.',
            'bunga_pinjaman.numeric' => 'Bunga pinjaman diisi dengan angka.',
            'bunga_pinjaman.regex' => 'Bunga pinjaman diisi dengan angka desimal. (Maks. 2 digit setelah koma)',
            'bunga_pinjaman.between' => 'Bunga pinjaman harus antara 0.03 (3%) sampai 1.00 (100%).',

            'maks_pinjaman.required' => 'Maksimal pinjaman wajib diisi.',
            'maks_pinjaman.min' => 'Minimal pinjaman adalah Rp.500.000',

            'maks_tenor.required' => 'Maksimal Tenor wajib diisi.',
            'maks_tenor.numeric' => 'Maksimal Tenor diisi dengan angka.',
            'maks_tenor.min' => 'Minimal tenor adalah 3 bulan.'
        ]);

        // ambil user id dari session (Asumsi pakai Auth)
        $data['id_user'] = Auth::user()->id_user;

        KonfigurasiPinjaman::updateOrCreate(
            ['id_user' => $data['id_user']],
            $data
        );

        return response()->json(['message' => 'Berhasil']);
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
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

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
        $konfig_bunga = KonfigurasiPinjaman::latest()->first();
        $bunga_pinjaman = $konfig_bunga->bunga_pinjaman;

        $besar_pinjaman = $pinjaman->besar_pinjaman;
        // Bunga tahunan default
        $bunga_bulanan = $bunga_pinjaman;
        // Jumlah cicilan
        $tenor = $pinjaman->tenor_pinjaman;
        //jumlah bunga
        $jumlah_bunga = $besar_pinjaman * $bunga_pinjaman;
        //total pinjaman
        $total_pembayaran = $besar_pinjaman + $jumlah_bunga;
        //pembayaran bulanan
        $pembayaran_bulanan = $total_pembayaran / $tenor;

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

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
    
    public function ubahStatusPinjaman(Request $request, $id_pinjaman)
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

    public function viewTransaksiPinjaman($user_id = null)
    {
        // Query dasar untuk semua user dengan pinjaman
        $users = User::with(['pinjaman.tanggungan.transaksiPinjaman'])
            ->has('pinjaman')
            ->get();

        // Data untuk view
        $data = [
            'title' => 'Transaksi Pinjaman',
            'users' => $users
        ];

        // Jika ada parameter user_id, tampilkan detail user tersebut
        if ($user_id) {
            $detailUser = User::with(['pinjaman.tanggungan.transaksiPinjaman' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
                ->findOrFail($user_id);

            $data['detailUser'] = $detailUser;
        }

        return view('roleAdmin.transaksiPinjaman', $data);
    }

    public function getDetailTransaksiPinjaman($user_id)
    {
        $user = User::with(['pinjaman.tanggungan.transaksiPinjaman'])->findOrFail($user_id);

        $tanggunganData = $user->pinjaman->flatMap(function ($pinjaman) {
            return collect($pinjaman->tanggungan)->map(function ($tanggungan) {
                return [
                    'total_pinjaman' => $tanggungan->total_pinjaman,
                    'angsuran' => $tanggungan->iuran_perBulan,
                    'keterangan' => $tanggungan->status_pinjaman,
                    'transaksi' => $tanggungan->transaksiPinjaman->map(function ($transaksi) {
                        return [
                            'jatuh_tempo' => $transaksi->jatuh_tempo ?? '-',
                            'tanggal_pembayaran' => $transaksi->tanggal_pembayaran ?? '-',
                            'status_transaksi' => $transaksi->keterangan ?? '-',
                        ];
                    }),
                ];
            });
        });

        return response()->json($tanggunganData);
    }

    public function viewTransaksiSimpanan()
    {
    $transaksiPokok = User::with(['simpananPokok.transaksiPokok'])
        ->has('simpananPokok')
        ->get();

        $data =[
            'title' => 'Transaksi Simpanan',
            'transaksiPokok' => $transaksiPokok,
        ];

    return view('roleAdmin.transaksiSimpanan', $data);
    }

    public function getDetailTransaksiSimpanan($user_id)
    {
        $user = User::with(['simpananPokok.transaksiPokok.simpananPokok'])->findOrFail($user_id);

        // Ambil semua transaksi_pokok dari seluruh simpanan_pokok user
        $transaksi = $user->simpananPokok->flatMap(function ($simpanan) {
            return $simpanan->transaksiPokok;
        })->map(function ($transaksi) {
            return [
                'iuran' => $transaksi->simpananPokok->iuran ?? null,
                'jatuh_tempo' => $transaksi->jatuh_tempo,
                'tanggal_pembayaran' => $transaksi->tanggal_pembayaran,
                'keterangan' => $transaksi->keterangan,
            ];
        });

        return response()->json($transaksi);
    }

    public function eksporPDFPinjaman()
    {
        // Ambil semua user yang punya pinjaman dan transaksinya
        $users = User::with(['pinjaman.tanggungan.transaksiPinjaman'])
            ->has('pinjaman')
            ->get();

        // Kirim ke view PDF
        $pdf = Pdf::loadView('roleAdmin.laporanPinjamanPDF', [
            'users' => $users
        ])->setPaper('A4', 'landscape'); // agar lebih lebar

        return $pdf->download('Laporan-Pinjaman-Anggota.pdf');
    }

    public function eksporPDFSimpanan()
    {
        $users = User::with('simpananPokok')->get(); // pastikan relasi simpanan tersedia
        $pdf = PDF::loadView('roleAdmin.laporanSimpananPDF', compact('users'))->setPaper('A4', 'portrait');
        return $pdf->download('Laporan-Simpanan-Anggota.pdf');
    }
}
