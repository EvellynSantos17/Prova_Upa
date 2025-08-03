<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setor extends Model
{
    use HasFactory;
    protected $table = 'setores';

    protected $fillable = ['nome'];

    /*
      * Get the users that belong to the setor.
    */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
