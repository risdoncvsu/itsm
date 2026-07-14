<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ServiceTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(string $portal = 'client', string $ticketType = 'erp_module')
    {
        $query = ServiceTicket::query()->latest();

        if ($portal === 'admin') {
            $query->where('ticket_type', 'nexora_support');
        } else {
            $query
                ->where('company_id', Auth::user()->company_id)
                ->where('ticket_type', $ticketType);
        }

        return view('service.service', [
            'portal' => $portal,
            'active' => 'service-desk',
            'ticketType' => $portal === 'admin' ? 'nexora_support' : $ticketType,
            'canCreateTicket' => $portal !== 'admin',
            'title' => $this->titleFor($portal, $ticketType),
            'subtitle' => $this->subtitleFor($portal, $ticketType),
            'tickets' => $query->get(),
        ]);
    }

    public function supportIndex()
    {
        return $this->index('client', 'nexora_support');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedTicket($request);
        $company = $this->companyForRequest($request);
        $ticketType = $request->input('ticket_type') === 'nexora_support'
            ? 'nexora_support'
            : 'erp_module';

        ServiceTicket::create($validated + [
            'company_id' => $company?->id,
            'created_by' => Auth::id(),
            'client_name' => $company?->company_name,
            'ticket_no' => $this->nextTicketNo(),
            'ticket_type' => $ticketType,
        ]);

        return back()->with('success', 'Ticket created successfully.');
    }

    public function update(Request $request, ServiceTicket $ticket): RedirectResponse
    {
        $this->authorizePortalAccess($ticket);

        $ticket->update($this->validatedTicket($request));

        return back()->with('success', 'Ticket updated successfully.');
    }

    private function validatedTicket(Request $request): array
    {
        return $request->validate([
            'requester' => ['nullable', 'string', 'max:255'],
            'module' => ['nullable', 'string', 'max:255'],
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

        if (! Auth::user()->company_id && $ticket->ticket_type !== 'nexora_support') {
            abort(403);
        }
    }

    private function nextTicketNo(): string
    {
        $nextId = (int) ServiceTicket::max('id') + 1;

        return 'NX-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }

    private function titleFor(string $portal, string $ticketType): string
    {
        if ($portal === 'admin') {
            return 'Nexora Support Desk';
        }

        return $ticketType === 'nexora_support'
            ? 'Ask Nexora Support'
            : 'ERP Module Tickets';
    }

    private function subtitleFor(string $portal, string $ticketType): string
    {
        if ($portal === 'admin') {
            return 'Support requests sent by company system admins to the Nexora root admin team.';
        }

        return $ticketType === 'nexora_support'
            ? 'Create tickets for Nexora root admins when your company needs platform-level help.'
            : 'Track tickets raised from ERP modules such as HR, Finance, Inventory, and Operations.';
    }
}
