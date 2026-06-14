<?php

namespace App\Http\Controllers\SuperAdmin;
 
use App\Http\Controllers\Controller;
use App\Models\Business;
 
class FeatureController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:superadmin']);
    // }
 
    public function index()
    {
        $businesses = Business::all();
        return view('superadmin.features', compact('businesses'));
    }
}