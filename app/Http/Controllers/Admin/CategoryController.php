<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->latest()->paginate(12);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Category::create($this->attributes($request));

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update($this->attributes($request, $category));

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    private function attributes(Request $request, ?Category $category = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'gender' => ['required', 'in:men,women,kids,unisex'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $attributes = [
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name'], $category),
            'description' => $data['description'] ?? null,
            'gender' => $data['gender'],
            'is_active' => $data['is_active'] ?? false,
        ];

        if ($request->hasFile('image')) {
            if ($category?->image && ! str_starts_with($category->image, 'http')) {
                Storage::disk('public')->delete($category->image);
            }
            $attributes['image'] = $request->file('image')->store('categories', 'public');
        }

        return $attributes;
    }

    private function uniqueSlug(string $name, ?Category $category = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (Category::where('slug', $slug)->when($category, fn ($q) => $q->where('id', '!=', $category->id))->exists()) {
            $slug = "{$original}-".$count++;
        }

        return $slug;
    }
}
