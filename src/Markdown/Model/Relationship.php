<?php

namespace Docs\Markdown\Model;

use Docs\Markdown\Item;
use Docs\Support\Markdown;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Relationship extends Item
{
    protected $relationship;

    protected $links = [
        HasOne::class         => 'https://laravel.com/docs/7.x/eloquent-relationships#one-to-one',
        BelongsTo::class      => 'https://laravel.com/docs/7.x/eloquent-relationships#one-to-one',
        HasMany::class        => 'https://laravel.com/docs/7.x/eloquent-relationships#one-to-many',
        BelongsToMany::class  => 'https://laravel.com/docs/7.x/eloquent-relationships#many-to-many',
        MorphTo::class        => 'https://laravel.com/docs/7.x/eloquent-relationships#one-to-one-polymorphic-relations',
        MorphMany::class      => 'https://laravel.com/docs/7.x/eloquent-relationships#one-to-many-polymorphic-relations',
        MorphToMany::class    => 'https://laravel.com/docs/7.x/eloquent-relationships#many-to-many-polymorphic-relations',
        HasOneThrough::class  => 'https://laravel.com/docs/7.x/eloquent-relationships#has-one-through',
        HasManyThrough::class => 'https://laravel.com/docs/7.x/eloquent-relationships#has-many-through',
    ];

    public function __construct(string $relation)
    {
        $this->relation = $relation;
    }

    public function toMarkdown()
    {
        if ($link = $this->getLink()) {
            return Markdown::link($this->getTitle(), $link)->toMarkdown();
        }

        return $this->getTitle();
    }

    public function getTitle()
    {
        return lcfirst(class_basename($this->relation));
    }

    public function getLink()
    {
        foreach ($this->links as $relation => $link) {
            if (instance_of($this->relation, $relation)) {
                return $link;
            }
        }
    }
}
