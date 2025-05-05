<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'type',
        'is_featured',
        'display_order'
    ];

    /**
     * Get featured items by type
     *
     * @param string $type poster|documentation
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFeatured($type = null, $limit = 6)
    {
        $query = self::where('is_featured', true)
                    ->orderBy('display_order')
                    ->orderBy('created_at', 'desc');
        
        if ($type) {
            $query->where('type', $type);
        }
        
        return $query->limit($limit)->get();
    }

    /**
     * Get all items by type
     *
     * @param string $type poster|documentation
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByType($type)
    {
        return self::where('type', $type)
                ->orderBy('display_order')
                ->orderBy('created_at', 'desc')
                ->get();
    }
}