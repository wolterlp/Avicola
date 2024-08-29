<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    //
    public function index()
    {
        $expenses = Expense::all();
        return view('expenses.create', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    
    public function store(Request $request)
    {
        $expense_date = $request->input('expense_date') ?? now();

        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        Expense::create([
            'user_id' => auth()->id(),
            'description' => $request->input('description'),
            'amount' => $request->input('amount'),
            'expense_date' => $request->input('expense_date'),
        ]);

        return redirect()->route('expenses.index')->with('success', 'Gasto registrado correctamente.');
    }
}
