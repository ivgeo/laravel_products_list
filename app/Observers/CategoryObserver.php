<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryObserver
{
    public function saving(Category $category): void
    {
        $namesByCode = Category::whereIn('code', Category::ancestorCodes($category->code))
            ->pluck('name', 'code');
        $namesByCode[$category->code] = $category->name;

        $category->full_name = $this->fullNameFor($category->code, $namesByCode);
    }

    public function saved(Category $category): void
    {
        if ($category->wasChanged(['name', 'code'])) {
            $this->cascadeToDescendants($category);
        }
    }

    private function cascadeToDescendants(Category $category): void
    {
        $namesByCode = Category::query()->pluck('name', 'code');

        Category::where('code', 'like', $category->code.'%')
            ->where('id', '!=', $category->id)
            ->get(['id', 'code'])
            ->each(function (Category $descendant) use ($namesByCode) {
                $descendant->newQuery()->whereKey($descendant->id)->update([
                    'full_name' => $this->fullNameFor($descendant->code, $namesByCode),
                    'updated_at' => now(),
                ]);
            });
    }

    /**
     * @param  Collection<string, string>  $namesByCode  code => name, covering $code and all its ancestors
     */
    private function fullNameFor(string $code, Collection $namesByCode): string
    {
        return collect([...Category::ancestorCodes($code), $code])
            ->map(fn (string $ancestorCode) => $namesByCode[$ancestorCode] ?? null)
            ->filter()
            ->implode(Category::FULL_NAME_SEPARATOR);
    }
}
