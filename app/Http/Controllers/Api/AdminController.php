<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;

class AdminController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Admin::latest()->get()
        ]);
    }
}