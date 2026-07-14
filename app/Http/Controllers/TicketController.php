<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ServiceTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(string $portal = 'client')
    {
        $query = ServiceTicket::query()->latest();

        if ($portal === 'client') {
            $query->where('company_id', Auth::user()->company_id);
        }

        return view('service.service', [
            'portal' => $portal,
            'active' => 'service-desk',
            'title' => $portal === 'admin' ? 'Client Service Desk' : 'Company Service Desk',
            'subtitle' => $portal === 'admin'
                ? 'Requests from client companies using Nexora ERP'
                : 'Internal ITSM requests for your company users',
            'tickets' => $query->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedTicket($request);
        $company = $this->companyForRequest($request);

        ServiceTicket::create($validated + [
            'company_id' => $company?->id,
            'created_by' => Auth::id(),
            'client_name' => $company?->company_name,
            'ticket_no' => $this->nextTicketNo(),
        ]);

        return back()->with('success', 'Ticket created successfully.');
    }

    public function update(Request $request, ServiceTicket $ticket): RedirectResponse
    {
        $this->authorizePortalAccess($ticket);

        $ticket->update($this->validatedTicket($request, true));

        return back()->with('success', 'Ticket updated successfully.');
    }

    private function validatedTicket(Request $request, bool $updating = false): array
    {
        return $request->validate([
            'requester' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'string', 'max:50'],
            'status' => ['required', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);
    }

    private function companyForRequest(Request $request): ?Company
    {
        if (Auth::user()->company_id) {
            return Company::find(Auth::user()->company_id);
        }

        return null;
    }

    private function authorizePortalAccess(ServiceTicket $ticket): void
    {
        if (Auth::user()->company_id && $ticket->company_id !== Auth::user()->company_id) {
            abort(403);
        }
    }

    private function nextTicketNo(): string
    {
        $nextId = (int) ServiceTicket::max('id') + 1;

        return 'NX-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }
}
