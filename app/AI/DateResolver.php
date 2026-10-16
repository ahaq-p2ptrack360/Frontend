<?php

namespace App\AI;

use Carbon\Carbon;
use InvalidArgumentException;

class DateResolver
{
    public function resolve(array $date): array
    {
        $type = $date['type'] ?? null;

        return match ($type) {
            'range' => $this->resolveRange($date),

            'month' => $this->resolveMonth($date),

            'quarter' => $this->resolveQuarter($date),

            'year' => $this->resolveYear($date),

            'relative' => $this->resolveRelative($date),

            'before' => $this->resolveBefore($date),

            'after' => $this->resolveAfter($date),

            default => throw new InvalidArgumentException(
                'Unsupported date type.'
            ),
        };
    }

    protected function resolveRange(array $date): array
    {
        if (
            empty($date['from']) ||
            empty($date['to'])
        ) {
            throw new InvalidArgumentException(
                'Date range requires from and to.'
            );
        }

        return [
            'from' => Carbon::parse($date['from'])
                ->startOfDay(),

            'to' => Carbon::parse($date['to'])
                ->endOfDay(),
        ];
    }

    protected function resolveMonth(array $date): array
    {
        $year = $date['year'] ?? null;
        $month = $date['month'] ?? null;

        $now = now();

        if ($year === 'current') {
            $year = $now->year;
        }

        if ($month === 'current') {
            $month = $now->month;
        }

        if (!$year || !$month) {
            throw new InvalidArgumentException(
                'Invalid month date.'
            );
        }

        $start = Carbon::create(
            (int) $year,
            (int) $month,
            1
        )->startOfMonth();

        return [
            'from' => $start,
            'to' => $start->copy()->endOfMonth(),
        ];
    }

    protected function resolveQuarter(array $date): array
    {
        $year = $date['year'] ?? null;
        $quarter = $date['quarter'] ?? null;

        $now = now();

        if ($year === 'current') {
            $year = $now->year;
        }

        if ($quarter === 'current') {
            $quarter = (int) ceil($now->month / 3);
        }

        if (!$year || !$quarter) {
            throw new InvalidArgumentException(
                'Invalid quarter date.'
            );
        }

        $quarterNumber = (int) $quarter;
        $month = (($quarterNumber - 1) * 3) + 1;
        $start = Carbon::create(
            (int) $year,
            $month,
            1
        )->startOfQuarter();

        return [
            'from' => $start->startOfDay(),
            'to' => $start->copy()->endOfQuarter()->endOfDay(),
        ];
    }

    protected function resolveYear(array $date): array
    {
        $year = $date['year'] ?? null;

        if ($year === 'current') {
            $year = now()->year;
        }

        if (!$year) {
            throw new InvalidArgumentException(
                'Invalid year date.'
            );
        }

        $start = Carbon::create(
            (int) $year,
            1,
            1
        )->startOfYear();

        return [
            'from' => $start,
            'to' => $start->copy()->endOfYear(),
        ];
    }

    protected function resolveRelative(array $date): array
    {
        $amount = (int) ($date['amount'] ?? 0);
        $unit = $date['unit'] ?? null;
        $direction = $date['direction'] ?? 'past';

        if ($amount <= 0 || !$unit) {
            throw new InvalidArgumentException(
                'Invalid relative date.'
            );
        }

        $start = now()->startOfDay();
        $end = now()->endOfDay();

        return match ($direction) {
            'past' => [
                'from' => $start->copy()->sub(
                    $unit,
                    $amount
                ),
                'to' => $end,
            ],

            'future' => [
                'from' => $start,
                'to' => $end->copy()->add(
                    $unit,
                    $amount
                ),
            ],

            default => throw new InvalidArgumentException(
                'Unsupported date direction.'
            ),
        };
    }

    protected function resolveBefore(array $date): array
    {
        $target = $date['target'] ?? 'today';
        $limit = Carbon::parse($target)->endOfDay();

        return [
            'from' => Carbon::create(1970, 1, 1)->startOfDay(),
            'to' => $limit,
        ];
    }

    protected function resolveAfter(array $date): array
    {
        $target = $date['target'] ?? 'today';
        $start = Carbon::parse($target)->startOfDay();

        return [
            'from' => $start,
            'to' => Carbon::parse('2999-12-31')->endOfDay(),
        ];
    }
}