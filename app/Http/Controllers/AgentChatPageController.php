<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class AgentChatPageController extends Controller
{
    public function index(): View
    {
        return view('agent-chat');
    }
}
