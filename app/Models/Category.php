<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Category Model
 * =========================================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'parent_id',
        'level'
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'parent_id' => 'integer',
            'level' => 'integer',
        ];
    }

    /**
     * Get the parent category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the direct children categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get the products for the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Check if the category is a parent category (level 1).
     *
     * @return bool
     */
    public function isParentCategory(): bool
    {
        return $this->level === 1;
    }

    /**
     * Check if the category is a child category (level 2).
     *
     * @return bool
     */
    public function isChildCategory(): bool
    {
        return $this->level === 2;
    }

    /**
     * Check if the category is a sub category (level 3).
     *
     * @return bool
     */
    public function isSubCategory(): bool
    {
        return $this->level === 3;
    }

    /**
     * Get all parent categories (level 1).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function mainCategories()
    {
        return static::where('level', 1)
            ->with(['children' => function ($query) {
                $query->with('children');
            }])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all child categories for a parent.
     *
     * @param int $parentId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function childCategories($parentId)
    {
        return static::where('parent_id', $parentId)
            ->where('level', 2)
            ->with('children')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all sub categories for a child.
     *
     * @param int $childId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function subCategories($childId)
    {
        return static::where('parent_id', $childId)
            ->where('level', 3)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get formatted name for display with full hierarchy.
     *
     * @return string
     */
    public function getFormattedName(): string
    {
        if ($this->isParentCategory()) {
            return $this->name;
        }

        if ($this->isChildCategory()) {
            return $this->parent->name . ' > ' . $this->name;
        }

        if ($this->isSubCategory()) {
            $child = $this->parent;
            $parent = $child->parent;
            return $parent->name . ' > ' . $child->name . ' > ' . $this->name;
        }

        return $this->name;
    }

    /**
     * Get validation rules for category.
     *
     * @return array<string, string>
     */
    public static function validationRules(): array
    {
        return [
            'name' => 'required|string|min:1|max:16',
            'parent_id' => 'nullable|exists:categories,id',
            'level' => 'nullable|integer|in:1,2,3'
        ];
    }

    /**
     * Get parent category or null if root.
     *
     * @return Category|null
     */
    public function getRootParent()
    {
        if ($this->isParentCategory()) {
            return $this;
        }

        if ($this->isChildCategory()) {
            return $this->parent;
        }

        if ($this->isSubCategory()) {
            return $this->parent->parent;
        }

        return null;
    }

    /**
     * Get all descendants (children and sub-children).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDescendants()
    {
        $descendants = collect();

        foreach ($this->children as $child) {
            $descendants->push($child);
            foreach ($child->children as $subChild) {
                $descendants->push($subChild);
            }
        }

        return $descendants;
    }
}
