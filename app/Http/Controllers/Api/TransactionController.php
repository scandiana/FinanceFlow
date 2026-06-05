<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return Transaction::all();
    }

    public function store(Request $request)
    {
        return Transaction::create($request->all());
    }

    public function show($id)
    {
        return Transaction::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $t = Transaction::findOrFail($id);
        $t->update($request->all());
        return $t;
    }

    public function destroy($id)
    {
        Transaction::destroy($id);
        return response()->json(['ok' => true]);
    }
}
