<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded=[];
    public function course(){
        return $this->hasMany(Course::class);
    }
    public function getImageUrlAttribute(){
        return asset('storage/images/'.$this->image);
    }
}
