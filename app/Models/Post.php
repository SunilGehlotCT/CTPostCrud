<?php

namespace App\Models;

use Shamaseen\Repository\Utility\Model as Model;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Post.
 */
class Post extends Model
{
    protected $table = 'posts';

    use HasSlug, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =[
		'user_id', 'title', 'slug', 'content', 'image'
	];

    protected ?array $sortables = [
        'created_at', 'updated_at'
    ];

    protected ?array  $filterables = [
        'user_id'
    ];

    protected ?array  $searchables = [
        'title'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at'
    ];

    /* ================== Route Key Name ================== */

    /**
     * Specify the route key name to be used in route model binding.
     *
     * @return string
     */
    // public function getRouteKeyName(){
    //     return 'slug';
    // }

    /* ================== Slug Configuration ================== */

    /**
     * Get the options for generating the slug.
     *
     * @return SlugOptions
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /* ================== Relationships ================== */

    /**
     * Get the comments for the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the user that owns the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
