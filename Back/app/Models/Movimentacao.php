<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimentacao extends Model
{
    protected $table = "movimentacoes";
    protected $fillable = [
        'item_id',
        'user_id',
        'motivo_retirada',
        'havera_devolucao',
        'tipo',
        'data_movimentacao',
    ];

    /**
     * Relação com o modelo Item.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Itens::class);
    }

    /**
     * Relação com o modelo User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
