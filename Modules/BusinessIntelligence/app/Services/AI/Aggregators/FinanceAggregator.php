<?php

namespace App\Services\AI\Aggregators;

use App\Services\Departments\FinanceService;

/**
 * Shapes Finance KPIs for AI consumption. Delegates all computation to
 * FinanceService — the same service the Finance dashboard tab uses —
 * so the AI never sees numbers that disagree with the UI.
 */
class FinanceAggregator
{
    public function __construct(protected FinanceService $financeService) {}

    /**
     * @return array<string, mixed>
     */
    public function summarize(): array
    {
        return $this->financeService->getKpiSummaryForAi();
    }
}
