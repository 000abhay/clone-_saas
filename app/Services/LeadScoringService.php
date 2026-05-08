<?php

namespace App\Services;

class LeadScoringService
{
    public function calculate(array $attributes): int
    {
        $score = 10;

        if (! empty($attributes['email'])) {
            $score += 10;
        }

        if (! empty($attributes['phone'])) {
            $score += 10;
        }

        if (($attributes['source'] ?? null) === 'referral') {
            $score += 20;
        }

        if (($attributes['source'] ?? null) === 'website') {
            $score += 10;
        }

        if (($attributes['status'] ?? null) === 'qualified') {
            $score += 25;
        }

        if (($attributes['status'] ?? null) === 'working') {
            $score += 15;
        }

        if (! empty($attributes['company_name'])) {
            $score += 10;
        }

        return min($score, 100);
    }
}
