<?php

namespace App\AI;

use InvalidArgumentException;

class QueryValidator
{
    protected array $allowedEntities = [
        'tickets',
        'users',
        'company',
        'status',
        'priority',
        'business_units',
        'customers',
        'vendors',
    ];

    protected array $allowedOperations = [
        'count',
        'list',
        'aggregate',
    ];

    protected array $allowedGroupBy = [
        'status',
        'priority',
        'vendor',
        'business_unit',
        'month',
        'day',
        'date',
    ];

    protected array $allowedChartTypes = [
        'bar',
        'line',
        'pie',
        'donut',
    ];

    public function validate(array $plan): array
    {
        if (!isset($plan['entity'])) {
            throw new InvalidArgumentException(
                'Query plan must contain an entity.'
            );
        }

        if (!in_array($plan['entity'], $this->allowedEntities, true)) {
            throw new InvalidArgumentException(
                'Entity is not allowed.'
            );
        }

        if (!isset($plan['operation'])) {
            throw new InvalidArgumentException(
                'Query plan must contain an operation.'
            );
        }

        if (!in_array($plan['operation'], $this->allowedOperations, true)) {
            throw new InvalidArgumentException(
                'Operation is not allowed.'
            );
        }

        $groupBy = $plan['group_by'] ?? $plan['groupBy'] ?? null;

        if (
            ($plan['operation'] ?? null) === 'aggregate'
            && $groupBy !== null
            && !in_array($groupBy, $this->allowedGroupBy, true)
        ) {
            throw new InvalidArgumentException(
                'Aggregate queries require a supported group_by value.'
            );
        }

        if (
            ($plan['operation'] ?? null) === 'aggregate'
            && $groupBy !== null
        ) {
            $plan['group_by'] = $groupBy;
        }

        if (isset($plan['chart_requested']) && !is_bool($plan['chart_requested'])) {
            throw new InvalidArgumentException(
                'chart_requested must be a boolean.'
            );
        }

        if (isset($plan['chart_type'])) {
            if (!in_array($plan['chart_type'], $this->allowedChartTypes, true)) {
                throw new InvalidArgumentException(
                    'Unsupported chart type.'
                );
            }
        }

        if (
            isset($plan['filters'])
            && !is_array($plan['filters'])
        ) {
            throw new InvalidArgumentException(
                'Filters must be an object.'
            );
        }

        return $plan;
    }
}