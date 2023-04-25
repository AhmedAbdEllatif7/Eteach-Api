<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function storeFile(Request $request)
    {
        $file = $request->file('file');
        $file_name = $file->getClientOriginalName();
        Message::create([
            'user_id' => Auth::user()->id,
            'file' =>$file_name,
            'message_text' => \auth()->user()->name,
        ]);

        $file->move(public_path('files/') , $file_name);
        return redirect()->back()->with(['success' => "تمت إضافة الملف بنجاح"]);
    }

    public function re()
    {
        return "sd";
    }
}
