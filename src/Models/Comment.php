<?php

namespace Module\Comment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Modules\CommentRealtime\Models\Comment
 *
 * @property int $id
 * @property int $customer_id
 * @property string|null $title
 * @property string $content
 * @property int $page_id
 * @property int $is_from_admin
 * @property bool $is_published
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property int $like
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Kalnoy\Nestedset\Collection|Comment[] $children
 * @property-read int|null $children_count
 * @property-read \Modules\CommentRealtime\Models\Page $page
 * @property-read Comment|null $parent
 * @method static \Kalnoy\Nestedset\Collection|static[] all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Comment d()
 * @method static \Kalnoy\Nestedset\Collection|static[] get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment newQuery()
 * @method static \Illuminate\Database\Query\Builder|Comment onlyTrashed()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIsFromAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIsShowFrontend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereLike($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Comment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Comment withoutTrashed()
 * @mixin \Eloquent
 */
class Comment extends Model
{
    use SoftDeletes, NodeTrait;

    protected $table = 'comment__comments';

    protected $fillable = [
        'customer_id',
        'parent_id',
        'title',
        'content',
        'is_from_admin',
        'page_id',
        'is_published',
        'like',
        'dislike',
        'customer_token',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function table(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
