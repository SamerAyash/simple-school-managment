<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $guarded=[];

    public function grade(){
        return $this->hasMany(Grade::class);
    }
    public function getImageUrlAttribute(){
        return asset('storage/images/'.$this->image);
    }
}
