<?php

namespace App\Http\Controllers;

use App\Models\LivrePrestation;
use Illuminate\Http\Request;

class LivrePrestationController extends Controller
{

    
    
    public function store(Request $request, $chevalId)
    {
        foreach ($request->prestations as $date => $prestationIds) {
            foreach ($prestationIds as $prestationId) {
                if (!empty($prestationId)) {
                    LivrePrestation::create([
                        'cheval_id' => $chevalId,
                        'prestation_id' => $prestationId,
                        'date_prestation' => $date,
                    ]);
                }
            }
        }

        return redirect()->route('chevaux.show', $chevalId)->with('success', 'Prestations affectées avec succès.');
    }

    public function destroy($id)
    {
        $livrePrestation = LivrePrestation::findOrFail($id);
        $livrePrestation->delete();

        return back()->with('success', 'Prestation annulée avec succès.');
    }
}
