<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
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
    public function transform(Seller $seller)
    {
        return [
            'identificador' =>  (int)$seller->id,
            'nombre' =>  (string)$seller->name,
            'correo' =>  (string)$seller->email,
            'verificado' =>  (int)$seller->verified,
            'fechaCreacion' =>  (string)$seller->created_at,
            'fechaActualizacion' => (string)$seller->updated_at,
            'fechaEliminacion' => isset($seller->deleted_at) ? (string) $seller->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('sellers.show', $seller->id),
                ],
                [
                    'rel' => 'seller.buyers',
                    'href' => route('sellers.buyers.index', $seller->id),
                ],
                [
                    'rel' => 'seller.categories',
                    'href' => route('sellers.categories.index', $seller->id),
                ],
                [
                    'rel' => 'seller.products',
                    'href' => route('sellers.products.index', $seller->id),
                ],
                [
                    'rel' => 'seller.transactions',
                    'href' => route('sellers.transactions.index', $seller->id),
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show', $seller->id),
                ],
            ],
        ];
    }

    public static function originalAttributeName($index)
    {
        $attribute = [
            'identificador' =>  'id',
            'nombre' =>  'name',
            'correo' => 'email',
            'verificado' => 'verified',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
        
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attribute = [
            'id'=>'identificador',
            'name'=>'nombre',
            'email'=>'correo',
            'verified'=>'verificado',
            'created_at'=>'fechaCreacion',
            'updated_at'=>'fechaActualizacion',
            'deleted_at'=>'fechaEliminacion',
        
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
