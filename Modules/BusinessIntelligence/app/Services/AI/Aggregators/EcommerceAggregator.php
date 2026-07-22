<?php

namespace App\Services\AI\Aggregators;

use App\Services\Departments\EcommerceService;

class SalesAggregator
{
    public function __construct(protected EcommerceService $ecommerceService)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function summarize(): array
    {
        return $this->ecommerceService->getKpiSummaryForAi();
    }
}
