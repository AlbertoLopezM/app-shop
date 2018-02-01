<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    

	public static $messages=[
    		'name.required' => 'Es necesario ingresar un nombre para la categoria.',
    		'name.min' => 'El nombre de la categoria debe tener al menos 3 caracteres.',
    		'description.max' => 'La descripcion corta solo admite hasta 200 caracteres.'
    	];

    	public static $rules=[
    		'name' => 'required|min:3',
    		'description' => 'max:200'
    	];

	protected $fillable = ['name', 'description'];

    //$category->products
    public function products()
    {
    	return $this->hasMany(Product::class);
    }

    public function getFeaturedImageUrlAttribute()
    {
        if($this->image)
            return '/images/categories/'.$this->image;

        //else
        $firstProduct = $this->products()->first();
        if($firstProduct)
            return $firstProduct->featured_image_url;
        
        return '/images/default.png';
    }
}
