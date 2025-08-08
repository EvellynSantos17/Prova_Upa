<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Itens extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'itens';

    protected $fillable = ['nome', 'descricao', 'codigo', 'setor_id'];

    public function estoque()
    {
        return $this->hasOne(Estoque::class, 'item_id');
    }

    public function setore()
    {
        return $this->belongsTo(Setor::class, 'setor_id');
    }

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }
}
