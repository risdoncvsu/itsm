<?php

namespace App\Services\AI\Aggregators;

use App\Services\Departments\ComplianceService;

/**
 * Shapes Compliance & Risk KPIs for AI consumption.
 */
class ComplianceAggregator
{
    public function __construct(protected ComplianceService $complianceService) {}

    /**
     * @return array<string, mixed>
     */
    public function summarize(): array
    {
        return $this->complianceService->getKpiSummaryForAi();
    }
}
