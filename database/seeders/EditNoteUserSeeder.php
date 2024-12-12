<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Order\Entities\Note;

class EditNoteUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Note::where('created_at','<=',Carbon::parse('2024-10-23 00:00:00'))->whereNull('user_id')->update(['user_id'=>1]);
    }
}
