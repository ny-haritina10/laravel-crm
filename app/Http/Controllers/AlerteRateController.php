<?php

namespace App\Http\Controllers;

use App\Services\AlerteRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlerteRateController extends Controller
{
    protected $alerteRateService;

    public function __construct(AlerteRateService $alerteRateService)
    {
        $this->alerteRateService = $alerteRateService;
    }

    public function index()
    {
        try {
            $token = session('token');
            if (!$token) {
                return redirect()->route('login')->with('error', 'Please log in to continue.');
            }

            $alerteRates = $this->alerteRateService->getAllAlerteRates($token);
            return view('alerte-rate.index', compact('alerteRates'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form to edit an AlerteRate
     */
    public function edit($id)
    {
        try {
            $token = session('token');
            if (!$token) {
                return redirect()->route('login')->with('error', 'Please log in to continue.');
            }

            $alerteRate = $this->alerteRateService->getAlerteRate($token, $id);
            return view('alerte-rate.edit', compact('alerteRate'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update an AlerteRate
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'percentage' => 'required|numeric|min:0|max:100',
            'alerte_rate_date' => 'nullable',
        ]);

        try {
            $token = session('token');
            if (!$token) {
                return redirect()->route('login')->with('error', 'Please log in to continue.');
            }

            $data = [
                'percentage' => $request->input('percentage'),
                'alerteRateDate' => $request->input('alerte_rate_date'),
            ];

            $this->alerteRateService->updateAlerteRate($token, $id, $data);
            return redirect()->route('alerte-rate.index', $id)->with('success', 'AlerteRate updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Delete an AlerteRate
     */
    public function destroy($id)
    {
        try {
            $token = session('token');
            if (!$token) {
                return redirect()->route('login')->with('error', 'Please log in to continue.');
            }

            $this->alerteRateService->deleteAlerteRate($token, $id);
            return redirect()->route('dashboard')->with('success', 'AlerteRate deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}