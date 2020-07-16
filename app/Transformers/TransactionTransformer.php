<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'identificador' =>  (int)$transaction->id,
            'cantidad' =>  (int)$transaction->quantity,
            'comprador' =>  (int)$transaction->buyer_id,
            'producto' =>  (int)$transaction->product_id,
            'fechaCreacion' =>  (string)$transaction->created_at,
            'fechaActualizacion' => (string)$transaction->updated_at,
            'fechaEliminacion' => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null,
        ];            
    }

    public static function originalAttributeName($index)
    {
        $attribute = [
            'identificador' =>  'id',
            'cantidad' =>  'quantity',
            'comprador' => 'buyer_id',
            'producto' => 'product_id',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
        
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
