<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Authの処理を使うために入れる
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable,HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'manage_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    // 変更を許可しないカラムのリスト
    protected $guarded = [
        'manage_id',
        'created',
    ];

    public function find($id,$columns = ['*'])
    {
        return $this->where($this->primaryKey,$id)->first($columns);
    }
}