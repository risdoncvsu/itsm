<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $clientId = (int) $request->user()->company_id;
        if ($request->isMethod('post')) {
            return redirect()->route('client.itsm.audit');
        }

        $audits = Schema::hasTable('erp_audit_logs')
            ? DB::table('erp_audit_logs')
                ->where('client_id', $clientId)
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($log): array {
                    $details = $this->decodeDetails($log->details);
                    $eventLabel = Str::of((string) $log->event)
                        ->afterLast('.')
                        ->replace(['_', '-'], ' ')
                        ->title();
                    $moduleLabel = Str::of((string) $log->module)
                        ->replace(['_', '-'], ' ')
                        ->title();
                    $actorLabel = $this->actorLabel($log->actor_id, $details);
                    $status = Str::contains(strtolower((string) $log->event), 'failed') ? 'Failed' : 'Completed';

                    return [
                        'reference' => '#ERP-' . str_pad((string) $log->id, 6, '0', STR_PAD_LEFT),
                        'title' => $eventLabel,
                        'scope' => $moduleLabel,
                        'auditor' => $actorLabel,
                        'date' => Carbon::parse($log->created_at)->format('F d, Y H:i'),
                        'summary' => $details['summary'] ?? ($details['note'] ?? 'Recorded through the ERP integration layer.'),
                        'status' => $status,
                        'status_class' => $status === 'Failed' ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800',
                    ];
                })
            : collect([]);

        $totalRecords = $audits->count();
        $moduleCount = $audits->pluck('scope')->unique()->count();
        $failedCount = $audits->where('status', 'Failed')->count();

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = strtolower($request->search);
            $audits = $audits->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item['reference']), $searchTerm) ||
                       str_contains(strtolower($item['title']), $searchTerm) ||
                       str_contains(strtolower($item['scope']), $searchTerm) ||
                       str_contains(strtolower($item['auditor']), $searchTerm) ||
                       str_contains(strtolower($item['summary']), $searchTerm);
            });
        }

        $currentStatus = $request->get('status', 'All');
        if ($currentStatus !== 'All') {
            $audits = $audits->filter(function ($item) use ($currentStatus) {
                return $item['status'] === $currentStatus;
            });
        }

        return view('audit', compact('audits', 'currentStatus', 'totalRecords', 'moduleCount', 'failedCount'));
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeDetails(mixed $details): array
    {
        if (is_array($details)) {
            return $details;
        }

        if (is_object($details)) {
            return (array) $details;
        }

        if (! is_string($details) || $details === '') {
            return [];
        }

        $decoded = json_decode($details, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function actorLabel(mixed $actorId, array $details): string
    {
        $actorName = $details['actor_name'] ?? $details['actor'] ?? null;

        if (is_string($actorName) && $actorName !== '') {
            return $actorName;
        }

        if (is_numeric($actorId)) {
            $user = User::query()->find((int) $actorId);

            if ($user) {
                return $user->name ?: $user->username ?: $user->email;
            }

            return 'User #' . $actorId;
        }

        return 'System';
    }
}
