<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public $table ='likes';
    use HasFactory;
    protected $fillable = [
        'post_id',
        'user_id',


    ];
    protected $hidden =[
        'created_at',
        'updated_at'
    ];
   
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->select('id','name','email','profile_url','short_bio','is_active');

    }

}
