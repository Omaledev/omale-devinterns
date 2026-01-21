<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolBankAccountSettingsController extends Controller
{
    /**
     * Show the settings form with current data.
     */
    public function edit()
    {
        // Getting the school of the currently logged-in School Admin
        $school = Auth::user()->school;

        return view('schooladmin.settings.edit', compact('school'));
    }

    /**
     * Update the school details.
     */
    public function update(Request $request)
    {
        $school = Auth::user()->school;

        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'account_name' => 'required|string|max:255',
        ]);

        $school->update([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
        ]);

        return back()->with('success', 'School payment details updated successfully!');
    }
}
