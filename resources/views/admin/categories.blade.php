@extends('layouts.app')

@section('content')

<style>
    :root {
        --color-bg-primary: #fcfcf9;
        --color-bg-secondary: #ffffff;
        --color-text-primary: #134252;
        --color-text-secondary: #626c71;
        --color-border: #e8e8e6;
        --color-accent: #208088;
        --color-accent-light: #32b8c6;
        --spacing-xs: 8px;
        --spacing-sm: 12px;
        --spacing-md: 16px;
        --spacing-lg: 20px;
        --spacing-xl: 24px;
        --spacing-2xl: 32px;
        --radius: 8px;
    }

    .categories-index-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl);
    }

    .categories-index-header {
        margin-bottom: var(--spacing-2xl);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .categories-index-title {
        font-size: 32px;
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
    }

    .categories-index-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: var(--spacing-xl);
        margin-bottom: var(--spacing-2xl);
    }

    .categories-index-card {
        background-color: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
    }

    .categories-index-card-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--color-accent);
        margin: 0 0 var(--spacing-lg) 0;
        padding-bottom: var(--spacing-md);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .categories-index-form-group {
        margin-bottom: var(--spacing-lg);
    }

    .categories-index-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: var(--spacing-sm);
    }

    .categories-index-input,
    .categories-index-select {
        width: 100%;
        padding: var(--spacing-md);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        font-size: 14px;
        color: var(--color-text-primary);
        background-color: var(--color-bg-secondary);
        font-family: inherit;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    .categories-index-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23134252' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right var(--spacing-md) center;
        background-size: 16px;
        padding-right: var(--spacing-2xl);
    }

    .categories-index-input:focus,
    .categories-index-select:focus {
        outline: none;
        border-color: var(--color-accent);
        box-shadow: 0 0 0 2px rgba(32, 128, 136, 0.1);
    }

    .categories-index-help-text {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin: var(--spacing-sm) 0 0 0;
    }

    .categories-index-submit {
        width: 100%;
        background-color: var(--color-accent);
        color: #ffffff;
        border: none;
        padding: var(--spacing-md) var(--spacing-lg);
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .categories-index-submit:hover {
        background-color: var(--color-accent-light);
    }

    .categories-index-empty {
        font-size: 14px;
        color: var(--color-text-secondary);
        text-align: center;
        padding: var(--spacing-lg);
        background-color: var(--color-bg-primary);
        border-radius: var(--radius);
    }

    .categories-index-list {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .categories-index-parent {
        background-color: var(--color-bg-primary);
        border: 2px solid var(--color-accent-light);
        border-radius: var(--radius);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
    }

    .categories-index-parent-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-lg);
        padding-bottom: var(--spacing-lg);
        border-bottom: 2px solid var(--color-accent-light);
    }

    .categories-index-parent-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--color-accent);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .categories-index-level-indicator {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 4px 8px;
        border-radius: var(--radius);
        background-color: var(--color-accent);
        color: white;
        margin-right: var(--spacing-sm);
    }

    .categories-index-children {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-lg);
    }

    .categories-index-child {
        background-color: var(--color-bg-secondary);
        border-left: 4px solid var(--color-accent);
        border-radius: var(--radius);
        padding: var(--spacing-md);
        margin-left: var(--spacing-xl);
    }

    .categories-index-child-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-md);
        padding-bottom: var(--spacing-md);
        border-bottom: 1px solid var(--color-border);
    }

    .categories-index-child-name {
        font-size: 15px;
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .categories-index-subcategories {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .categories-index-subcategory {
        background-color: var(--color-bg-primary);
        border-left: 4px solid var(--color-accent-light);
        border-radius: var(--radius);
        padding: var(--spacing-md);
        margin-left: var(--spacing-lg);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .categories-index-subcategory-name {
        font-size: 14px;
        color: var(--color-text-primary);
        font-weight: 500;
    }

    .categories-index-delete-btn {
        background-color: #ffffff;
        color: var(--color-text-primary);
        border: 1px solid var(--color-border);
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .categories-index-delete-btn:hover {
        background-color: #fecaca;
        border-color: #ef4444;
        color: #dc2626;
    }

    .inline {
        display: inline;
    }

    @media (max-width: 768px) {
        .categories-index-container {
            padding: var(--spacing-md);
        }

        .categories-index-grid {
            grid-template-columns: 1fr;
        }

        .categories-index-title {
            font-size: 24px;
        }

        .categories-index-parent-header,
        .categories-index-child-header {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--spacing-md);
        }

        .categories-index-parent-header form,
        .categories-index-child-header form {
            width: 100%;
        }

        .categories-index-delete-btn {
            width: 100%;
        }
    }
</style>

<div class="categories-index-container">
    <div class="categories-index-header">
        <h1 class="categories-index-title">Erebus Marketplace Script Category Management</h1>
        <p style="color: var(--color-text-secondary); margin: var(--spacing-md) 0 0 0;">Manage Parent Categories, Child Categories, and Sub Categories</p>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div style="background-color: #fee2e2; border: 1px solid #ef5350; color: #c62828; padding: var(--spacing-lg); border-radius: var(--radius); margin-bottom: var(--spacing-lg);">
            <strong>Error:</strong>
            <ul style="margin: var(--spacing-md) 0 0 0; padding-left: var(--spacing-lg);">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div style="background-color: #c8e6c9; border: 1px solid #43a047; color: #2e7d32; padding: var(--spacing-lg); border-radius: var(--radius); margin-bottom: var(--spacing-lg);">
            {{ session('success') }}
        </div>
    @endif

    <div class="categories-index-grid">
        <!-- Create Category Form -->
        <div class="categories-index-card">
            <h2 class="categories-index-card-title">Create New Category</h2>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="categories-index-form-group">
                    <label for="name" class="categories-index-label">Category Name</label>
                    <input type="text" name="name" id="name" 
                           class="categories-index-input"
                           required minlength="1" maxlength="16" 
                           value="{{ old('name') }}"
                           placeholder="Enter category name">
                    <p class="categories-index-help-text">Between 1 and 16 characters</p>
                </div>

                <div class="categories-index-form-group">
                    <label for="parent_id" class="categories-index-label">Parent Category (Optional)</label>
                    <select name="parent_id" id="parent_id" class="categories-index-select">
                        <option value="">None (Parent Category)</option>
                        @php
                            function printCategoryOptions($categories, $prefix = '') {
                                foreach ($categories as $category) {
                                    $displayPrefix = '';
                                    if ($category->level === 2) {
                                        $displayPrefix = '→ ';
                                    } elseif ($category->level === 3) {
                                        $displayPrefix = '→→ ';
                                    }
                                    echo '<option value="' . $category->id . '" ' . (old('parent_id') == $category->id ? 'selected' : '') . '>' . $prefix . $displayPrefix . $category->name . '</option>';
                                    if ($category->children->isNotEmpty()) {
                                        printCategoryOptions($category->children, $prefix . '  ');
                                    }
                                }
                            }
                        @endphp
                        @foreach ($mainCategories as $category)
                            <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} (Parent)
                            </option>
                            @foreach ($category->children as $child)
                                <option value="{{ $child->id }}" {{ old('parent_id') == $child->id ? 'selected' : '' }}>
                                    → {{ $child->name }} (Child)
                                </option>
                                @foreach ($child->children as $sub)
                                    <option value="{{ $sub->id }}" {{ old('parent_id') == $sub->id ? 'selected' : '' }}>
                                        → → {{ $sub->name }} (Sub)
                                    </option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select>
                    <p class="categories-index-help-text">Leave empty to create a Parent Category, select a Parent for Child Category, or select a Child for Sub Category</p>
                </div>

                <button type="submit" class="categories-index-submit">
                    Create Category
                </button>
            </form>
        </div>
    </div>

    <!-- Categories List -->
    <div class="categories-index-card">
        <h2 class="categories-index-card-title">Category Hierarchy</h2>
        
        @if($mainCategories->isEmpty())
            <p class="categories-index-empty">No categories found. Create your first Parent Category above.</p>
        @else
            <div class="categories-index-list">
                @foreach($mainCategories as $parentCategory)
                    <div class="categories-index-parent">
                        <!-- Parent Category -->
                        <div class="categories-index-parent-header">
                            <div style="display: flex; align-items: center;">
                                <span class="categories-index-level-indicator">Parent</span>
                                <span class="categories-index-parent-name">{{ $parentCategory->name }}</span>
                            </div>
                            <form action="{{ route('admin.categories.delete', $parentCategory) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="categories-index-delete-btn" onclick="return confirm('Delete this category and all its subcategories?')">
                                    Delete
                                </button>
                            </form>
                        </div>

                        <!-- Child Categories -->
                        @if($parentCategory->children->isNotEmpty())
                            <div class="categories-index-children">
                                @foreach($parentCategory->children as $childCategory)
                                    <div class="categories-index-child">
                                        <div class="categories-index-child-header">
                                            <div style="display: flex; align-items: center;">
                                                <span class="categories-index-level-indicator" style="background-color: var(--color-accent-light);">Child</span>
                                                <span class="categories-index-child-name">{{ $childCategory->name }}</span>
                                            </div>
                                            <form action="{{ route('admin.categories.delete', $childCategory) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="categories-index-delete-btn" onclick="return confirm('Delete this category and all its subcategories?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Sub Categories -->
                                        @if($childCategory->children->isNotEmpty())
                                            <div class="categories-index-subcategories">
                                                @foreach($childCategory->children as $subCategory)
                                                    <div class="categories-index-subcategory">
                                                        <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
                                                            <span class="categories-index-level-indicator" style="background-color: #9ccc65; margin-right: 0;">Sub</span>
                                                            <span class="categories-index-subcategory-name">{{ $subCategory->name }}</span>
                                                        </div>
                                                        <form action="{{ route('admin.categories.delete', $subCategory) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="categories-index-delete-btn">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p style="font-size: 12px; color: var(--color-text-secondary); margin: var(--spacing-md) 0 0 var(--spacing-lg);">No Sub Categories yet</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p style="font-size: 12px; color: var(--color-text-secondary); margin: var(--spacing-md) 0;">No Child Categories yet</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection
