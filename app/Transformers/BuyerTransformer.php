<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
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
    public function transform(Buyer $buyer)
    {
        return [
            'identificador' =>  (int)$buyer->id,
            'nombre' =>  (string)$buyer->name,
            'correo' =>  (string)$buyer->email,
            'verificado' =>  (int)$buyer->verified,
            'fechaCreacion' =>  (string)$buyer->created_at,
            'fechaActualizacion' => (string)$buyer->updated_at,
            'fechaEliminacion' => isset($buyer->updated_at) ? (string) $user->deleted_at : null,
        
        ];
    }
}
