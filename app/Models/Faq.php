<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faq';

    protected $fillable = [
        'pertanyaan',
        'jawaban',
        'admin_id'
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }
}
