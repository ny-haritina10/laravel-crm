<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ImportDuplicateController extends Controller
{
    protected $api = 'http://localhost:8080/api/dashboard/import_duplicate';

    public function index()
    {
        return view('import.form');
    }

    public function submit(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:json|max:10240'
            ]);

            $token = Session::get('token');
            if (!$token) {
                return redirect()->back()->with('error', 'Please login to CRM first');
            }

            $file = $request->file('file');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->attach(
                'export',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post($this->api);

            if ($response->successful()) {
                return redirect()->back()->with('success', $response->body());
            } else {
                return redirect()->back()->with('error', 'API Error: ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}