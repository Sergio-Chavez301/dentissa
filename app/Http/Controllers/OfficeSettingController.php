<?php

namespace App\Http\Controllers;

use App\Models\OfficeSetting;
use Illuminate\Http\Request;

class OfficeSettingController extends Controller
{
    public function edit()
    {
        $settings = OfficeSetting::current();

        return view('admin.office-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'clinic_name' => ['required', 'string', 'max:150'],
            'clinic_address' => ['required', 'string', 'max:500'],
            'map_url' => ['nullable', 'url', 'max:1000'],
            'whatsapp_phone' => ['required', 'string', 'regex:/^\+?[0-9\s().-]{8,20}$/'],
        ], [
            'whatsapp_phone.regex' => 'Ingresa el número con clave de país, por ejemplo +52 55 1234 5678.',
        ]);

        OfficeSetting::current()->update($validated);

        return redirect()->route('admin.office-settings.edit')
            ->with('success', 'Los datos del consultorio se actualizaron correctamente.');
    }
}