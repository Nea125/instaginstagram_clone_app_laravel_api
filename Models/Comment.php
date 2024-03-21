<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $table ='comments';
    use HasFactory;
    protected $fillable = [
        'post_id',
        'user_id',
        'comment'


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
        return $this->belongsTo(User::class)->select('id','name','profile_url');

    }
}
