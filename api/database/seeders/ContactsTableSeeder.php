<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()

    {
        \App\Models\Contact::create([
            'name' => 'テスト太郎',
            'furigana' => 'てすとたろう',
            'email' => 'test@example.com',
            'tel' => '00000000000',
            'message' => 'これはテストメッセージです。'
        ]);
    }
}
