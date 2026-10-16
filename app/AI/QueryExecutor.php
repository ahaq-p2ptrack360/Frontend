<?php

namespace App\AI;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class QueryExecutor
{
    public function __construct(
        protected DateResolver $dateResolver
    ) {
    }

    public function execute(
        array $plan,
        int $companyId,
        int $userId
    ): mixed {

        if ($plan['entity'] !== 'tickets') {
            throw new InvalidArgumentException(
                'Only tickets are supported currently.'
            );
        }

        $query = DB::table('tickets')
            ->where('tickets.company_id', $companyId)
            ->whereNull('tickets.deleted_at');

        /*
        |--------------------------------------------------------------------------
        | MY TICKETS
        |--------------------------------------------------------------------------
        */

        if (($plan['scope'] ?? null) === 'my') {

            $query->where(function ($q) use ($userId) {

                $q->where(
                    'tickets.created_by',
                    $userId
                )->orWhere(
                    'tickets.assigned_to',
                    $userId
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        $filters = $plan['filters'] ?? [];

        if (!empty($filters['status'])) {

            $statusId = $this->resolveOptionId(
                'status',
                $filters['status'],
                $companyId
            );

            if (!$statusId) {
                return 0;
            }

            $query->where(
                'tickets.status_id',
                $statusId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PRIORITY
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['priority'])) {

            $priorityId = $this->resolveOptionId(
                'priority',
                $filters['priority'],
                $companyId
            );

            if (!$priorityId) {
                return 0;
            }

            $query->where(
                'tickets.priority_id',
                $priorityId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATE
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date'])) {

            $date = $filters['date'];

            $field = $date['field']
                ?? 'created_at';

            $allowedFields = [
                'created_at',
                'completed_date',
                'due_date',
            ];

            if (!in_array(
                $field,
                $allowedFields,
                true
            )) {
                throw new InvalidArgumentException(
                    'Invalid date field.'
                );
            }

            $resolved = $this->dateResolver->resolve(
                $date
            );

            $query->whereBetween(
                'tickets.' . $field,
                [
                    $resolved['from'],
                    $resolved['to'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | EXECUTE
        |--------------------------------------------------------------------------
        */

        return match ($plan['operation']) {

            'count' => $query->count(),

            'list' => $query
                ->select([
                    'tickets.id',
                    'tickets.title',
                    'tickets.created_at',
                    'tickets.completed_date',
                    'tickets.due_date',
                    'tickets.status_id',
                    'tickets.priority_id',
                ])
                ->limit(50)
                ->get(),

            'aggregate' => $this->aggregate($query, $plan, $companyId, $userId),

            default => throw new InvalidArgumentException(
                'Unsupported operation.'
            ),
        };
    }

    protected function aggregate(
        \Illuminate\Database\Query\Builder $query,
        array $plan,
        int $companyId,
        int $userId
    ): array {
        $groupBy = $plan['group_by'] ?? $plan['groupBy'] ?? null;
        $filters = $plan['filters'] ?? [];

        if (!empty($filters['status'])) {
            $statusId = $this->resolveOptionId(
                'status',
                $filters['status'],
                $companyId
            );

            if ($statusId === null) {
                return 0;
            }
        }

        if (!empty($filters['priority'])) {
            $priorityId = $this->resolveOptionId(
                'priority',
                $filters['priority'],
                $companyId
            );

            if ($priorityId === null) {
                return 0;
            }
        }

        if ($groupBy === 'status') {
            $rows = DB::table('status')
                ->where('status.company_id', $companyId)
                ->leftJoin('tickets', function ($join) use ($companyId, $userId, $plan) {
                    $join->on('status.id', '=', 'tickets.status_id')
                        ->where('tickets.company_id', $companyId)
                        ->whereNull('tickets.deleted_at');

                    if (($plan['scope'] ?? null) === 'my') {
                        $join->where(function ($q) use ($userId) {
                            $q->where('tickets.created_by', $userId)
                                ->orWhere('tickets.assigned_to', $userId);
                        });
                    }

                    $filters = $plan['filters'] ?? [];

                    if (!empty($filters['priority'])) {
                        $priorityId = $this->resolveOptionId(
                            'priority',
                            $filters['priority'],
                            $companyId
                        );

                        if ($priorityId) {
                            $join->where('tickets.priority_id', $priorityId);
                        }
                    }

                    if (!empty($filters['date'])) {
                        $date = $filters['date'];
                        $field = $date['field'] ?? 'created_at';
                        $resolved = $this->dateResolver->resolve($date);
                        $join->whereBetween('tickets.' . $field, [$resolved['from'], $resolved['to']]);
                    }
                })
                ->select(['status.title as status', DB::raw('COUNT(tickets.id) as count')])
                ->groupBy('status.id', 'status.title')
                ->orderBy('status.title')
                ->get();

            return $rows->map(fn ($row) => [
                'status' => $row->status,
                'count' => (int) $row->count,
            ])->all();
        }

        if ($groupBy === 'priority') {
            $rows = DB::table('priority')
                ->where('priority.company_id', $companyId)
                ->leftJoin('tickets', function ($join) use ($companyId, $userId, $plan) {
                    $join->on('priority.id', '=', 'tickets.priority_id')
                        ->where('tickets.company_id', $companyId)
                        ->whereNull('tickets.deleted_at');

                    if (($plan['scope'] ?? null) === 'my') {
                        $join->where(function ($q) use ($userId) {
                            $q->where('tickets.created_by', $userId)
                                ->orWhere('tickets.assigned_to', $userId);
                        });
                    }

                    $filters = $plan['filters'] ?? [];

                    if (!empty($filters['status'])) {
                        $statusId = $this->resolveOptionId(
                            'status',
                            $filters['status'],
                            $companyId
                        );

                        if ($statusId) {
                            $join->where('tickets.status_id', $statusId);
                        }
                    }

                    if (!empty($filters['date'])) {
                        $date = $filters['date'];
                        $field = $date['field'] ?? 'created_at';
                        $resolved = $this->dateResolver->resolve($date);
                        $join->whereBetween('tickets.' . $field, [$resolved['from'], $resolved['to']]);
                    }
                })
                ->select(['priority.title as priority', DB::raw('COUNT(tickets.id) as count')])
                ->groupBy('priority.id', 'priority.title')
                ->orderBy('priority.title')
                ->get();

            return $rows->map(fn ($row) => [
                'priority' => $row->priority,
                'count' => (int) $row->count,
            ])->all();
        }

        if ($groupBy === 'vendor') {
            $rows = DB::table('vendors')
                ->where('vendors.company_id', $companyId)
                ->leftJoin('tickets', function ($join) use ($companyId, $userId, $plan) {
                    $join->on('vendors.id', '=', 'tickets.vendor_id')
                        ->where('tickets.company_id', $companyId)
                        ->whereNull('tickets.deleted_at');

                    if (($plan['scope'] ?? null) === 'my') {
                        $join->where(function ($q) use ($userId) {
                            $q->where('tickets.created_by', $userId)
                                ->orWhere('tickets.assigned_to', $userId);
                        });
                    }

                    $filters = $plan['filters'] ?? [];

                    if (!empty($filters['status'])) {
                        $statusId = $this->resolveOptionId(
                            'status',
                            $filters['status'],
                            $companyId
                        );

                        if ($statusId) {
                            $join->where('tickets.status_id', $statusId);
                        }
                    }

                    if (!empty($filters['priority'])) {
                        $priorityId = $this->resolveOptionId(
                            'priority',
                            $filters['priority'],
                            $companyId
                        );

                        if ($priorityId) {
                            $join->where('tickets.priority_id', $priorityId);
                        }
                    }

                    if (!empty($filters['date'])) {
                        $date = $filters['date'];
                        $field = $date['field'] ?? 'created_at';
                        $resolved = $this->dateResolver->resolve($date);
                        $join->whereBetween('tickets.' . $field, [$resolved['from'], $resolved['to']]);
                    }
                })
                ->select(['vendors.name as vendor', DB::raw('COUNT(tickets.id) as count')])
                ->groupBy('vendors.id', 'vendors.name')
                ->orderBy('vendors.name')
                ->get();

            return $rows->map(fn ($row) => [
                'vendor' => $row->vendor,
                'count' => (int) $row->count,
            ])->all();
        }

        if ($groupBy === 'month') {
            $dateSql = DB::getDriverName() === 'sqlite'
                ? "strftime('%Y-%m', tickets.created_at)"
                : "DATE_FORMAT(tickets.created_at, '%Y-%m')";

            $rows = $query
                ->select([
                    DB::raw($dateSql . ' as month_key'),
                    DB::raw('COUNT(*) as count'),
                ])
                ->groupBy(DB::raw($dateSql))
                ->orderBy(DB::raw($dateSql))
                ->get();

            return $rows->map(function ($row) {
                $monthKey = $row->month_key;
                $monthLabel = \Carbon\Carbon::parse($monthKey . '-01')
                    ->translatedFormat('M Y');

                return [
                    'month' => $monthLabel,
                    'count' => (int) $row->count,
                ];
            })->all();
        }

        if ($groupBy === 'day' || $groupBy === 'date') {
            $dateField = $filters['date']['field'] ?? 'created_at';

            $allowedFields = [
                'created_at',
                'completed_date',
                'due_date',
            ];

            if (!in_array($dateField, $allowedFields, true)) {
                throw new InvalidArgumentException(
                    'Invalid date field.'
                );
            }

            $dateSql = DB::getDriverName() === 'sqlite'
                ? "strftime('%Y-%m-%d', tickets.{$dateField})"
                : "DATE_FORMAT(tickets.{$dateField}, '%Y-%m-%d')";

            $rows = $query
                ->select([
                    DB::raw($dateSql . ' as date_key'),
                    DB::raw('COUNT(*) as count'),
                ])
                ->whereNotNull('tickets.' . $dateField)
                ->groupBy(DB::raw($dateSql))
                ->orderBy(DB::raw($dateSql))
                ->get();

            return $rows->map(function ($row) {
                return [
                    'date' => \Carbon\Carbon::parse($row->date_key)->translatedFormat('d M'),
                    'count' => (int) $row->count,
                ];
            })->all();
        }

        throw new InvalidArgumentException(
            'Aggregate queries require a supported group_by value.'
        );
    }

    protected function resolveOptionId(
        string $table,
        string $title,
        int $companyId
    ): int | null {
        return DB::table($table)
            ->where('company_id', $companyId)
            ->where('title', $title)
            ->value('id');
    }
}