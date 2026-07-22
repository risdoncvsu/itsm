<?php

namespace App\Services\AI\Aggregators;

use App\Services\Departments\ItsmService;

/**
 * Shapes ITSM KPIs for AI consumption.
 */
class ItsmAggregator
{
    public function __construct(protected ItsmService $itsmService) {}

    /**
     * @return array<string, mixed>
     */
    public function summarize(): array
    {
        return $this->itsmService->getKpiSummaryForAi();
    }
}
