<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SimpananPokok;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Register',
        ];
        return view('auth.register', $data);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'usertype' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users',
            'NIP' => 'required|digits_between:1,17|max:255|unique:users',
            'jenis_kelamin' => 'required|string',
            'alamat' => 'required|string|max:255',
            'no_tlp' => 'required|digits_between:1,15',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'usertype.required' => 'Jenis pengguna wajib diisi.',
            'usertype.string' => 'Jenis pengguna harus berupa teks.',
            'usertype.max' => 'Jenis pengguna tidak boleh lebih dari 15 karakter.',
            
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
            'no_tlp.digits_between' => 'Nomor telepon wajib diisi dengan angka dan tanpa spasi',

            'password.required' => 'Kata sandi wajib diisi.',
            'password.string' => 'Kata sandi harus berupa teks.',
            'password.min' => 'Kata sandi harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'usertype' => $request->usertype,
            'email' => $request->email,
            'NIP' => $request->NIP,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'no_tlp' => $request->no_tlp,
            'password' => bcrypt($request->password),
        ]);

        $this->createSimpananPokok($user->id_user);

        return response()->json(['success' => true]);  

    }

    protected function createSimpananPokok($id_user)
    {
        SimpananPokok::create([
            'id_user' => $id_user,
            'iuran' => 100000,
            'total_simpanan' => 0,
            'status_simpanan' => 'Lunas'
        ]);

        return redirect()->route('dataAnggota');
    }
}
