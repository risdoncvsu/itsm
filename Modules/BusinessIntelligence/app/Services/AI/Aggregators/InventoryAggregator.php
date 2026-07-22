<?php

namespace App\Services\AI\Aggregators;

use App\Services\Departments\InventoryService;

/**
 * Shapes Inventory KPIs for AI consumption.
 */
class InventoryAggregator
{
    public function __construct(protected InventoryService $inventoryService) {}

    /**
     * @return array<string, mixed>
     */
    public function summarize(): array
    {
        return $this->inventoryService->getKpiSummaryForAi();
    }
}
