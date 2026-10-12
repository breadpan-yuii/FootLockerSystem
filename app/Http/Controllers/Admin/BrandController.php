<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::withCount('products')->latest()->paginate(12);

        return view('admin.brands.index', compact('brands'));
    }

    public function create(): View
    {
        return view('admin.brands.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Brand::create($this->attributes($request));

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $brand->update($this->attributes($request, $brand));

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
    }

    private function attributes(Request $request, ?Brand $brand = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image', 'max:4096'],
        ]);

        $attributes = [
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name'], $brand),
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? false,
        ];

        if ($request->hasFile('logo')) {
            if ($brand?->logo && ! str_starts_with($brand->logo, 'http')) {
                Storage::disk('public')->delete($brand->logo);
            }
            $attributes['logo'] = $request->file('logo')->store('brands', 'public');
        }

        return $attributes;
    }

    private function uniqueSlug(string $name, ?Brand $brand = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (Brand::where('slug', $slug)->when($brand, fn ($q) => $q->where('id', '!=', $brand->id))->exists()) {
            $slug = "{$original}-".$count++;
        }

        return $slug;
    }
}
