<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact; // Contactモデルを使用

class ContactController extends Controller
{
public function store(Request $request)
{
// バリデーション
    $validatedData = $request->validate([
        'name' => 'required|max:255',
        'furigana' => 'required|max:255',
        'email' => 'required|email|max:255',
        'tel' => 'required|numeric',
        'message' => 'required',
    ]);
// データの保存
Contact::create($validatedData);

// 成功した場合のレスポンス
return response()->json(['message' => 'お問い合わせありがとうございます。'], 200);
}
}
