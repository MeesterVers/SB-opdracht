<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
		'name', 'item_code', 'description', 'brand', 'category', 'price', 'image_1_url', 'stock_quantity', 'tags', 'status'
	];
}
