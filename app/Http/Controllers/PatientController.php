<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Cita;
use App\Models\Oferta;
use App\Models\OfficeSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $patient = Patient::with([
            'user',
            'citas.servicio',
            'citas.casoClinico',
            'casosClinicos.citas.servicio',
        ])
            ->where('user_id', $user->id)
            ->first();

        if (!$patient) {
            return view('patients.sin-perfil');
        }

        $ofertas = Oferta::where('activo', true)
            ->orderBy('orden')
            ->orderBy('id')
            ->take(3)
            ->get();

        return view('dashboard.paciente', compact('patient', 'ofertas'));
    }

    public function index()
    {
        $this->ensureStaffAccess();

        $patients = Patient::all();
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        $this->ensureStaffAccess();

        return view('patients.create');
    }

public function store(Request $request)
    {
        $this->ensureStaffAccess();

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:usuarios,email',
            'fecha_nacimiento' => 'required|date',
            'alergias' => 'nullable|string',
            'enfermedades' => 'nullable|string',
            'tratamientos' => 'nullable|string',
        ], [
            'email.unique' => 'Este correo electrónico ya está registrado en el sistema de usuarios.'
        ]);

        $usernameBase = Str::slug($validated['nombre'] . '.' . $validated['apellidos'], '');
        $username = $usernameBase . rand(100, 999);
        while (User::where('username', $username)->exists()) {
            $username = $usernameBase . rand(100, 999);
        }

        $passwordTemporal = Str::random(8);

        $usuario = User::create([
            'nombre'    => $validated['nombre'],
            'apellidos' => $validated['apellidos'],
            'username'  => $username,
            'email'     => $validated['email'] ?? 'paciente_' . time() . '@dentissa.com',
            'password'  => Hash::make($passwordTemporal),
            'role_id'   => 3,
            'activo'    => 1,
        ]);

        $validated['user_id'] = $usuario->id;

        $patient = Patient::create($validated);

        session([
            'temp_password' => $passwordTemporal,
            'temp_telefono' => $patient->telefono,
            'temp_nombre'   => $patient->nombre,            
            'temp_username' => $usuario->username,
        ]);

        return redirect()->route('patients.show', $patient->id)->with('success', 'Paciente y su cuenta de usuario creados exitosamente.');
    }

    public function show(string $id)
    {
        $query = Patient::with(['citas', 'casosClinicos']);

        if ((int) Auth::user()->role_id === 3) {
            $query->where('user_id', Auth::id());
        }

        $patient = $query->findOrFail($id);
        $officeSettings = OfficeSetting::current();

        return view('patients.show', compact('patient', 'officeSettings'));
    }

    public function edit(string $id)
    {
        $this->ensureStaffAccess();

        $patient = Patient::findOrFail($id);
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, string $id)
    {
        $this->ensureStaffAccess();

        $patient = Patient::findOrFail($id);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'fecha_nacimiento' => 'required|date',
            'alergias' => 'nullable|string',
            'enfermedades' => 'nullable|string',
            'tratamientos' => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'Datos actualizados correctamente.');
    }

    public function destroy(string $id)
    {
        $this->ensureStaffAccess();

        $patient = Patient::findOrFail($id);

        if ($patient->user_id) {
            User::where('id', $patient->user_id)->delete();
        }

        if (method_exists($patient, 'casosClinicos')) {
            $patient->casosClinicos()->delete();
        }

        Cita::where('paciente_id', $patient->id)->delete();

        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Paciente, su cuenta de usuario, citas y casos clínicos fueron eliminados correctamente.');
    }

    public function enviarCredencialesWhatsApp(string $id)
    {
        abort_unless((int) Auth::user()->role_id === 1, 403);

        $patient = Patient::findOrFail($id);

        if (!$patient->user_id) {
            $passwordTemporal = Str::random(8);
            $usuario = User::create([
                'nombre'    => $patient->nombre,
                'apellidos' => $patient->apellidos,
                'username'  => Str::slug($patient->nombre . '.' . $patient->apellidos . '-' . rand(100, 999), ''),
                'email'     => $patient->email ?? 'paciente_' . $patient->id . '@dentissa.com',
                'password'  => Hash::make($passwordTemporal),
                'role_id'   => 3,
                'activo'    => 1,
            ]);

            $patient->update(['user_id' => $usuario->id]);
        } else {
            $usuario = User::findOrFail($patient->user_id);
            $passwordTemporal = Str::random(8);
            $usuario->update([
                'password' => Hash::make($passwordTemporal)
            ]);
        }

        session()->flash('temp_password', $passwordTemporal);
        session()->flash('temp_telefono', $patient->telefono);
        session()->flash('temp_nombre', $patient->nombre);
        session()->flash('temp_username', $usuario->username);

        return redirect()->route('patients.show', $patient->id)->with('success', 'Contraseña temporal generada. Puedes enviarla por WhatsApp.');
    }

    public function updateCredentials(Request $request, string $id)
    {
        abort_unless((int) Auth::user()->role_id === 1, 403);

        $patient = Patient::with('user')->findOrFail($id);
        abort_unless($patient->user, 422, 'El paciente aún no tiene una cuenta de acceso.');

        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:usuarios,username,' . $patient->user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'password.confirmed' => 'Las contraseñas escritas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $patient->user->username = $validated['username'];

        if (!empty($validated['password'])) {
            $patient->user->password = Hash::make($validated['password']);
        }

        $patient->user->save();

        session()->forget(['temp_password', 'temp_telefono', 'temp_nombre', 'temp_username']);
        session()->flash('temp_telefono', $patient->telefono);
        session()->flash('temp_nombre', $patient->nombre);
        session()->flash('temp_username', $patient->user->username);

        if (!empty($validated['password'])) {
            session()->flash('temp_password', $validated['password']);
        }

        return redirect()->route('patients.show', $patient->id)->with('success', 'Credenciales del paciente actualizadas.');
    }

    private function ensureStaffAccess(): void
    {
        abort_unless((int) Auth::user()->role_id !== 3, 403);
    }
}