<?php

namespace App\Services\AI\Aggregators;

use App\Services\Departments\ManufacturingService;

/**
 * Shapes Manufacturing KPIs for AI consumption.
 */
class ManufacturingAggregator
{
    public function __construct(protected ManufacturingService $manufacturingService) {}

    /**
     * @return array<string, mixed>
     */
    public function summarize(): array
    {
        return $this->manufacturingService->getKpiSummaryForAi();
    }
}
