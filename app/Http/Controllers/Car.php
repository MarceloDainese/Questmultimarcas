<?php

namespace App\Http\Controllers;

use App\Models\Car as ModelsCar;
use Illuminate\Http\Request;

class Car extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->query('id');
        $carDelete = ModelsCar::find($id);
        $carDelete->delete();

        return redirect()->route('list');
    }
}
