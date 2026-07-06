<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __invoke(): View
    {
        return view('contact', [
            'profile' => Profile::first(),
        ]);
    }
}
