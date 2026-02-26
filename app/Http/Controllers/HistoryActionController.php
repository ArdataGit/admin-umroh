<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\HistoryAction;

class HistoryActionController extends Controller
{
    public function index()
    {
        $historyActions = HistoryAction::with('user')->latest()->get();
        return view('pages.history-action.index', [
            'title' => 'History Action',
            'historyActions' => $historyActions
        ]);
    }
}
