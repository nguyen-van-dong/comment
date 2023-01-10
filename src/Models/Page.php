<?php

namespace Module\Comment\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Module\Comment\Models\Page
 *
 * @property int $id
 * @property string|null $page_title
 * @property string $page_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Kalnoy\Nestedset\Collection|\Module\Comment\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page wherePageTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page wherePageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Page extends Model
{
    protected $table = 'comment__pages';

    protected $fillable = [
        'page_title',
        'page_url',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
