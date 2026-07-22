<?php

namespace App\Services\AI\Aggregators;

/**
 * Top of the aggregation pipeline: Database → Department Aggregators →
 * DashboardAggregator → AIManager → Gemini. Collects every department's
 * summarized KPIs into a single payload, ready to hand to AIManager.
 * This is the ONLY class AIManager asks for KPI data — it never queries
 * department services or models directly.
 */
class DashboardAggregator
{
    public function __construct(
        protected FinanceAggregator $finance,
        protected InventoryAggregator $inventory,
        protected ManufacturingAggregator $manufacturing,
        protected ProcurementAggregator $procurement,
        protected ComplianceAggregator $compliance,
        protected ItsmAggregator $itsm,
        protected SalesAggregator $sales,
    ) {}

    /**
     * @return array<string, array<string, mixed>>
     */
    public function collectAll(): array
    {
        return [
            'finance' => $this->finance->summarize(),
            'inventory' => $this->inventory->summarize(),
            'manufacturing' => $this->manufacturing->summarize(),
            'procurement' => $this->procurement->summarize(),
            'compliance' => $this->compliance->summarize(),
            'itsm' => $this->itsm->summarize(),
            'sales' => $this->sales->summarize(),
        ];
    }

    /**
     * Collect KPIs for a single department by key, used by the
     * event-driven single-department insight path (Package 7).
     *
     * @return array<string, mixed>
     */
    public function collectDepartment(string $department): array
    {
        return match ($department) {
            'finance' => $this->finance->summarize(),
            'inventory' => $this->inventory->summarize(),
            'manufacturing' => $this->manufacturing->summarize(),
            'procurement' => $this->procurement->summarize(),
            'compliance' => $this->compliance->summarize(),
            'itsm' => $this->itsm->summarize(),
            'sales' => $this->sales->summarize(),
            default => throw new \InvalidArgumentException("Unknown department [{$department}]"),
        };
    }
}
