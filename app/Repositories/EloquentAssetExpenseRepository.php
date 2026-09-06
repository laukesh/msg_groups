<?php

namespace App\Repositories;

use App\Models\AssetExpense;

class EloquentAssetExpenseRepository implements AssetExpenseRepositoryInterface
{
    protected AssetExpense $model;

    public function __construct(AssetExpense $model)
    {
        $this->model = $model;
    }

    /**
     * Get all expenses.
     */
    public function all(array $filters = [])
    {
        return $this->buildQuery($filters)
            ->latest('expense_date')
            ->get();
    }

    /**
     * Get paginated expenses.
     */
    public function paginate(array $filters = [], int $perPage = 15)
    {
        return $this->buildQuery($filters)
            ->latest('expense_date')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Find expense.
     */
    public function find(int $id)
    {
        return $this->model
            ->with('asset')
            ->find($id);
    }

    /**
     * Create expense.
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update expense.
     */
    public function update(int $id, array $data)
    {
        $expense = $this->model->find($id);

        if (!$expense) {
            return null;
        }

        $expense->update($data);

        return $expense->fresh(['asset']);
    }

    /**
     * Delete expense.
     */
    public function delete(int $id): bool
    {
        $expense = $this->model->find($id);

        if (!$expense) {
            return false;
        }

        return (bool) $expense->delete();
    }

    /**
     * Get expenses belonging to one asset.
     */
    public function getByAsset(
        int $assetId,
        array $filters = [],
        int $perPage = 15
    ) {
        $filters['asset_id'] = $assetId;

        return $this->buildQuery($filters)
            ->latest('expense_date')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Build query.
     */
    protected function buildQuery(array $filters = [])
    {
        $query = $this->model->with('asset');

        /*
        |--------------------------------------------------------------------------
        | Asset
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['asset_id'])) {
            $query->where(
                'asset_id',
                $filters['asset_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'expense_type',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'description',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'vendor_name',
                    'like',
                    "%{$search}%"
                );

                $q->orWhereHas(
                    'asset',
                    function ($assetQuery) use ($search) {

                        $assetQuery
                            ->where(
                                'asset_code',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'asset_name',
                                'like',
                                "%{$search}%"
                            );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['status'])) {

            $query->where(
                'status',
                $filters['status']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Operating Expense
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['is_operating_expense']) &&
            $filters['is_operating_expense'] !== ''
        ) {

            $query->where(
                'is_operating_expense',
                $filters['is_operating_expense']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_from'])) {

            $query->whereDate(
                'expense_date',
                '>=',
                $filters['date_from']
            );
        }

        if (!empty($filters['date_to'])) {

            $query->whereDate(
                'expense_date',
                '<=',
                $filters['date_to']
            );
        }

        return $query;
    }
}