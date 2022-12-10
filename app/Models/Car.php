<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Car extends Model
{
  use HasApiTokens, HasFactory;
  protected $fillable = [
    'user_id',
    'name',
    'model'
  ];
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
