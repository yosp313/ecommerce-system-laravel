<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Product;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProductsResolver
{
    /** @param  array{}  $args */
public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $query = Product::query();

        $this->applySearch($query, $args['search'] ?? null);
        $this->applyCategory($query, $args['category_id'] ?? null);
        $this->applySorting($query, $args['sort_order'] ?? null);

        $perPage = $args['first'] ?? 10;
        $products = $query->paginate($perPage);
        return ["data" => $products, "paginatorInfo" =>[
            "currentPage" => $products->currentPage(),
            "lastPage" => $products->lastPage(),
            "perPage" => $products->perPage(),
            "total" => $products->total(),
        ]];
    }

    /**
     * Apply search filter to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $search
     */
    private function applySearch($query, ?string $search): void
    {
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }
    }

    /**
     * Apply category filter to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $categoryId
     */
    private function applyCategory($query, $categoryId): void
    {
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
    }

    /**
     * Apply sorting to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $sortOrder
     */
    private function applySorting($query, ?string $sortOrder): void
    {
        switch ($sortOrder) {
            case 'created_at_desc':
                $query->orderBy('created_at', 'DESC');
                break;
            case 'created_at_asc':
                $query->orderBy('created_at', 'ASC');
                break;
            case 'price_desc':
                $query->orderBy('price', 'DESC');
                break;
            case 'price_asc':
                $query->orderBy('price', 'ASC');
                break;
            default:
                $query->orderBy('created_at', 'DESC');
                break;
        }
    }}
