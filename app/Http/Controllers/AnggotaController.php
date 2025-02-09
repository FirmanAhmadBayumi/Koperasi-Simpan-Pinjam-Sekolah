<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pinjaman;
use Illuminate\View\View;
use App\Models\Tanggungan;
use Illuminate\Http\Request;
use App\Models\SimpananPokok;
use App\Models\TransaksiPokok;
use App\Models\PencairanPinjaman;
use App\Models\TransaksiPinjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\ProfileUpdateRequest;
use App\Exports\ExportTransaksiPinjamanAnggota;
use App\Exports\ExportTransaksiSimpananAnggota;

class AnggotaController extends Controller
{
    // ------------------------------------- DASHBOARD ----------------------------------------
    public function index()
    {
        $totalSimpanan = SimpananPokok::where('id_user', Auth::user()->id_user)->sum('total_simpanan');
        $totalSHU = User::where('id_user', Auth::user()->id_user)->sum('shu');
        $data = [
            'title' => 'Dashboard',
            'simpanan' => $totalSimpanan,
            'shu' => $totalSHU,
        ];
        return view('roleAnggota.dashboard', $data);
    }

    public function getChartData()
    {
        $userId = Auth::id();

        // Ambil dan proses data total pinjaman untuk user yang sedang login
        $totalPinjaman = DB::table('pinjaman')
            ->select(DB::raw('MONTH(tgl_pengajuan) as month'), DB::raw('SUM(besar_pinjaman) as total'))
            ->where('id_user', $userId)
            ->where('keterangan', 'Disetujui')
            ->groupBy(DB::raw('MONTH(tgl_pengajuan)'))
            ->pluck('total', 'month');

        // Ambil dan proses data total simpanan pokok untuk user yang sedang login
        $totalSimpanan = DB::table('simpanan_pokoks')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total_simpanan) as total'))
            ->where('id_user', $userId)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        // Inisialisasi array data dengan 0 untuk setiap bulan
        $pinjamanData = array_fill(1, 12, 0);
        $simpananData = array_fill(1, 12, 0);

        // Isi array dengan data yang didapat dari query
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

    // --------------------------------- PENGAJUAN PINJAMAN -----------------------------------
    public function pengajuan(Request $request)
    {
        $pinjaman = Pinjaman::where('id_user', Auth::user()->id_user)->orderBy('id_pinjaman', 'asc')->get();
        $data = [
            'title' => 'Pengajuan',
        ];
        return view('roleAnggota.pengajuan', $data, compact('pinjaman'));
    }
    public function createPengajuan(Request $request)
    {
        // Cek apakah ada pengajuan yang masih 'Diproses'
        $pengajuanDiproses = Pinjaman::where('id_user', Auth::user()->id_user)
            ->where('keterangan', 'Diproses')
            ->exists();

        // Cek apakah ada tanggungan yang masih 'Belum Lunas'
        $tanggungan = Tanggungan::with('pinjaman.user')
            ->whereHas('pinjaman', function ($query) {
                $query->where('id_user', Auth::user()->id_user);
            })
            ->where('status_pinjaman', 'Belum Lunas')->exists();

        if ($pengajuanDiproses) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Anda memiliki pengajuan yang sedang <b>DIPROSES</b>.',
            ]);
        } else if ($tanggungan) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Anda memiliki tanggungan pinjaman yang <b>BELUM LUNAS</b>.',
            ]);
        }

        // Validasi data pengajuan
        $validatedData = $request->validate([
            'tgl_pengajuan' => 'required|date_format:Y-m-d H:i:s',
            'besar_pinjaman' => 'required|integer|max:100000000',
            'tenor_pinjaman' => 'required|integer|max:50',
        ]);
        $validatedData['id_user'] = Auth::user()->id_user;
        $validatedData['keterangan'] = 'Diproses';

        Pinjaman::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan berhasil ditambahkan.',
        ]);
    }

    // --------------------------------- PENCAIRAN PINJAMAN -----------------------------------
    public function pencairanPinjaman()
    {
        $statusTombol = $this->cekPencairanPinjaman();

        // Menampilkan data pencairan pinjaman
        $riwayatPencairanPinjaman = PencairanPinjaman::with('pinjaman.user')
            ->whereHas('pinjaman', function ($query) {
                $query->where('id_user', Auth::user()->id_user);
            })->get();

        // Ambil pinjaman aktif (yang belum lunas atau sedang berjalan)
        $pinjamanAktif = Pinjaman::where('id_user', Auth::user()->id_user)
            ->whereNotIn('keterangan', ['Lunas']) // Mengecualikan yang sudah lunas
            ->orderBy('tgl_pengajuan', 'desc')
            ->first();

        $data = [
            'title' => 'Pengajuan Pencairan Pinjaman',
            'statusTombol' => $statusTombol,
            'pinjamanAktif' => $pinjamanAktif
        ];

        return view('roleAnggota.pencairanPinjaman', $data, compact('riwayatPencairanPinjaman'));
    }

    public function cekPencairanPinjaman()
    {
        // Cek apakah user sudah pernah melakukan pinjaman
        $cekPinjaman = Pinjaman::where('id_user', Auth::user()->id_user)->exists();

        // Jika belum pernah melakukan pinjaman sama sekali, tombol disabled
        if (!$cekPinjaman) {
            return 'disabled';
        }

        // Cek apakah ada pinjaman yang sudah diajukan dan disetujui
        $pinjamanDisetujui = Pinjaman::where('id_user', Auth::user()->id_user)
            ->where('keterangan', 'Disetujui')
            ->first();

        if ($pinjamanDisetujui) {
            // Cek apakah pinjaman ini sudah memiliki pencairan pinjaman aktif
            $cekPencairan = PencairanPinjaman::where('id_pinjaman', $pinjamanDisetujui->id_pinjaman)->exists();

            if ($cekPencairan) {
                return 'disabled'; // Jika sudah ada pencairan, tombol tetap disabled
            }

            return 'enabled'; // Jika belum ada pencairan, tombol bisa diklik
        }

        // Cek apakah ada pinjaman lain yang belum lunas (selain yang "Disetujui")
        $cekStatus = Pinjaman::where('id_user', Auth::user()->id_user)
            ->whereNotIn('keterangan', ['Lunas', 'Disetujui']) // Pinjaman belum lunas tapi bukan "Disetujui"
            ->exists();

        if ($cekStatus) {
            return 'disabled';
        }

        return 'disabled';
    }

    public function buatPengajuanPencairanPinjaman(Request $request)
    {
        // Validasi input
        $request->validate([
            'tgl_pengajuan' => 'required|date',
            'metode_pengiriman_pinjaman' => 'required|in:Transfer Rekening,Tunai',
            'nomor_rekening' => $request->metode_pengiriman_pinjaman == 'Transfer Rekening' ? 'required|numeric' : 'nullable',
            'nama_bank' => $request->metode_pengiriman_pinjaman == 'Transfer Rekening' ? 'required|string' : 'nullable',
        ]);

        // Cek apakah user memiliki pinjaman aktif
        $pinjamanAktif = Pinjaman::where('id_user', Auth::user()->id_user)
            ->where('keterangan', '!=', 'Lunas')->first();

        if (!$pinjamanAktif) {
            return redirect()->back()->with('error', 'Tidak ada pinjaman aktif yang bisa dicairkan.');
        }

        // Simpan pengajuan ke tabel pencairan_pinjaman
        $pencairan = new PencairanPinjaman();
        $pencairan->id_pinjaman = $pinjamanAktif->id_pinjaman;
        $pencairan->tgl_pengajuan = $request->tgl_pengajuan;
        $pencairan->metode_pengiriman_pinjaman = $request->metode_pengiriman_pinjaman;

        // Simpan data rekening jika metode transfer
        if ($request->metode_pengiriman_pinjaman == 'Transfer Rekening') {
            $pencairan->nomor_rekening = $request->nomor_rekening;
            $pencairan->nama_bank = $request->nama_bank;
        }

        $pencairan->save();

        return redirect()->back()->with('success', 'Pengajuan pencairan berhasil diajukan.');
    }


    // --------------------------------- TANGGUNGAN ANGGOTA -----------------------------------
    public function tanggungan()
    {
        $transaksiPokok = TransaksiPokok::with('simpananPokok.user')
            ->whereHas('simpananPokok', function ($query) {
                $query->where('keterangan', 'Belum Lunas')
                    ->where('id_user', Auth::user()->id_user);
            })->get();

        $transaksiPinjaman = TransaksiPinjaman::with('tanggungan.pinjaman.user')
            ->whereHas('tanggungan.pinjaman', function ($query) {
                $query->where('status_pinjaman', 'Belum Lunas')
                    ->where('id_user', Auth::user()->id_user);
            })->get();

        $cekPinjaman = TransaksiPinjaman::with('tanggungan.pinjaman.user')
            ->whereHas('tanggungan.pinjaman', function ($query) {
                $query->where('id_user', Auth::user()->id_user);
            })->where('keterangan', '!=', 'Lunas')->get();

        $tanggungan = Tanggungan::with('pinjaman.user')
            ->whereHas('pinjaman', function ($query) {
                $query->where('id_user', Auth::user()->id_user);
            })
            ->where('status_pinjaman', 'Belum Lunas')
            ->first();

        if ($tanggungan) {
            \Midtrans\Config::$serverKey = config('midtrans.serverKey');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $besar_pinjaman = $tanggungan->sisa_pinjaman - (($tanggungan->bunga_pinjaman / $tanggungan->pinjaman->tenor_pinjaman) * $tanggungan->sisa_tenor);

            $paramsLunas = array(
                'transaction_details' => array(
                    'order_id' => rand(),
                    'gross_amount' => ceil($besar_pinjaman),
                ),
                'customer_details' => array(
                    'first_name' => $tanggungan->pinjaman->user->nama,
                    'email' => $tanggungan->pinjaman->user->email,
                ),
            );
            $snapTokenLunas = \Midtrans\Snap::getSnapToken($paramsLunas);

            // Update snapTokenLunas attribute
            $tanggungan->snap_tokenLunas = $snapTokenLunas;
            $tanggungan->save();
        }

        $data = [
            'title' => 'Tanggungan',
            'transaksiPinjaman' => $transaksiPinjaman,
            'transaksiPokok' => $transaksiPokok,
            'tanggungan' => $tanggungan,
            'cekPinjaman' => $cekPinjaman,
        ];

        return view('roleAnggota.tanggungan', $data);
    }

    public function updateSimpanan(Request $request, $id_transaksiPokok)
    {
        $transaksiPokok = TransaksiPokok::findOrFail($id_transaksiPokok);
        $tanggungan = $transaksiPokok->simpananPokok;

        // Periksa apakah status pembayarannya adalah "lunas" atau semacamnya
        $statusPembayaran = $request->input('status');
        if ($statusPembayaran == 'Lunas') {
            $tanggungan->total_simpanan += $tanggungan->iuran;
            $tanggungan->status_simpanan = 'Lunas';

            $tanggungan->save();
        }

        // Update keterangan dan tanggal pembayaran di transaksi pinjaman
        $transaksiPokok->keterangan = $statusPembayaran;
        $transaksiPokok->tanggal_pembayaran = now()->timezone('Asia/Jakarta');
        $transaksiPokok->save();

        return redirect()->route('tanggungan.view');
    }

    public function updatePinjaman(Request $request, $id_transaksiPinjaman)
    {
        $transaksiPinjaman = TransaksiPinjaman::findOrFail($id_transaksiPinjaman);
        $tanggungan = $transaksiPinjaman->tanggungan;

        $shuPeminjam = 0.1 * ($tanggungan->bunga_pinjaman / $tanggungan->pinjaman->tenor_pinjaman);
        $sisaShu = 0.9 * ($tanggungan->bunga_pinjaman / $tanggungan->pinjaman->tenor_pinjaman);

        // Periksa apakah status pembayarannya adalah "lunas" atau semacamnya
        $statusPembayaran = $request->input('status');
        if ($statusPembayaran == 'Lunas') {
            $tanggungan->sisa_pinjaman -= $tanggungan->iuran_perBulan;
            $tanggungan->sisa_tenor -= 1;

            // Shu Peminjam
            $tanggungan->pinjaman->user->shu += $shuPeminjam;
            $tanggungan->pinjaman->user->save();

            // Periksa apakah sisa pinjaman menjadi 0 atau kurang, jika iya maka set status ke "Lunas"
            if ($tanggungan->sisa_pinjaman <= 0) {
                $tanggungan->sisa_pinjaman = 0;
                $tanggungan->status_pinjaman = 'Lunas';
            }
            $tanggungan->save();

            // Distribusikan 90% SHU ke seluruh user kecuali user yang melakukan pembayaran
            $users = User::where('id_user', '!=', $tanggungan->pinjaman->user->id_user)
                ->where('usertype', '!=', 'admin')->get();

            if ($users->count() > 0) {
                $shuPerUser = $sisaShu / $users->count();
                foreach ($users as $user) {
                    $user->shu += $shuPerUser;
                    $user->save();
                }
            }
        }

        // Update keterangan dan tanggal pembayaran di transaksi pinjaman
        $transaksiPinjaman->keterangan = $statusPembayaran;
        $transaksiPinjaman->tanggal_pembayaran = now()->timezone('Asia/Jakarta');
        $transaksiPinjaman->save();

        return redirect()->route('tanggungan.view');
    }

    public function updatePinjamanLunas(Request $request)
    {
        $tanggungan = Tanggungan::with('pinjaman.user')
            ->whereHas('pinjaman', function ($query) {
                $query->where('id_user', Auth::user()->id_user);
            })->where('status_pinjaman', 'Belum Lunas')->firstOrFail();

        $statusPembayaran = $request->input('status');
        if ($statusPembayaran == 'Lunas') {
            $tanggungan->sisa_pinjaman = 0;
            $tanggungan->sisa_tenor = 0;
            $tanggungan->status_pinjaman = 'Lunas';

            $tanggungan->save();
        }

        // Mengupdate semua transaksi yang berkaitan dengan tanggungan ini
        $transaksiPinjaman = $tanggungan->transaksiPinjaman()->get();
        foreach ($transaksiPinjaman as $transaksi) {
            // Perbarui keterangan dan tanggal pembayaran jika tanggal_pembayaran kosong
            if (is_null($transaksi->tanggal_pembayaran)) {
                $transaksi->keterangan = $statusPembayaran;
                $transaksi->tanggal_pembayaran = now()->timezone('Asia/Jakarta');
                $transaksi->save();
            }
        }

        return redirect()->route('tanggungan.view');
    }

    // --------------------------------- RIWAYAT PEMINJAMAN -----------------------------------
    public function history()
    {
        $history = Tanggungan::with('pinjaman.user')
            ->whereHas('pinjaman', function ($query) {
                $query->where('keterangan', 'Disetujui')
                    ->where('id_user', Auth::user()->id_user);
            })->get();

        $data = [
            'title' => 'History',
        ];
        return view('roleAnggota.history', $data, compact('history'));
    }

    // ----------------------------------- DATA TRANSAKSI -------------------------------------
    public function viewTransaksiSimpanan()
    {
        $transaksiPokok = TransaksiPokok::with('simpananPokok.user')
            ->whereHas('simpananPokok', function ($query) {
                $query->where('keterangan', 'Lunas')
                    ->where('id_user', Auth::user()->id_user);
            })->get();

        $data = [
            'title' => 'Transaksi Simpanan'
        ];

        return view('roleAnggota.transaksiSimpanan', $data, compact('transaksiPokok'));
    }

    public function viewTransaksiPinjaman()
    {
        $transaksiPinjaman = TransaksiPinjaman::with('tanggungan.pinjaman.user')
            ->whereHas('tanggungan.pinjaman', function ($query) {
                $query->where('id_user', Auth::user()->id_user);
            })->where('keterangan', 'Lunas')->get();

        $data = [
            'title' => 'Transaksi Simpanan'
        ];

        return view('roleAnggota.transaksiPinjaman', $data, compact('transaksiPinjaman'));
    }

    // --------------------------------------- PROFILE ----------------------------------------
    public function viewUser(Request $request): View
    {
        $data = [
            'title' => 'Profile',
        ];
        return view('roleAnggota.profile.view', $data, [
            'user' => $request->user()
        ]);
    }
    public function updateUser(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.view')->with('status', 'profile-updated');
    }
    public function updatePassword(Request $request): RedirectResponse
    {
        $messages = [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ];

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], $messages);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
