<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    public static function insertData($data){
        $value= customer::where('name', $data['name'])->get();

        if($value->count() == 0){

            customer::insert($data);
        }
    }
}
