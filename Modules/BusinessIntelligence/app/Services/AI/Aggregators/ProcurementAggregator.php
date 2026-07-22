<?php

namespace App\Services\AI\Aggregators;

use App\Services\Departments\ProcurementService;

/**
 * Shapes Procurement KPIs for AI consumption.
 */
class ProcurementAggregator
{
    public function __construct(protected ProcurementService $procurementService) {}

    /**
     * @return array<string, mixed>
     */
    public function summarize(): array
    {
        return $this->procurementService->getKpiSummaryForAi();
    }
}
