<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slugcategory extends Model
{
    use HasFactory;
    protected $table = 'slugs_category';

    public function get_slug_category(){
        return Slugcategory::from('slugs_category')
                ->select('slugs_category.id', 'slugs_category.category')
                ->where('slugs_category.status', 'A')
                ->where('slugs_category.is_deleted', 'N')
                ->orderBy('slugs_category.category', 'ASC')
                ->get()->toArray();
    }
}
