<aside class="sidebar">
  <div class="sidebar-title">Procurement</div>
  <div class="sidebar-desc">Manage purchase orders, suppliers, and requisitions.</div>

  <a href="{{ route('procurement.dashboard') }}" class="nav-item {{ request()->routeIs('procurement.dashboard') ? 'active' : '' }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="2"/><rect x="13" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="2"/><rect x="3" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="2"/><rect x="13" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="2"/></svg>
    Dashboard
  </a>

  <a href="{{ route('procurement.purchase-orders') }}" class="nav-item {{ request()->routeIs('procurement.purchase-orders') ? 'active' : '' }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M4 4h16v16H4z" stroke="currentColor" stroke-width="2"/><path d="M8 9h8M8 13h8" stroke="currentColor" stroke-width="2"/></svg>
    Purchase Orders
    <span class="nav-badge">{{ \Modules\Procurement\Models\PurchaseOrder::where('status', 'pending')->count() }}</span>
  </a>

  <a href="{{ route('procurement.suppliers') }}" class="nav-item {{ request()->routeIs('procurement.suppliers') ? 'active' : '' }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M3 21V8l9-5 9 5v13" stroke="currentColor" stroke-width="2"/><path d="M9 21v-6h6v6" stroke="currentColor" stroke-width="2"/></svg>
    Suppliers
  </a>

  <a href="{{ route('procurement.requisitions') }}" class="nav-item {{ request()->routeIs('procurement.requisitions') ? 'active' : '' }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M6 3h9l3 3v15H6z" stroke="currentColor" stroke-width="2"/><path d="M9 11h6M9 15h6" stroke="currentColor" stroke-width="2"/></svg>
    Requisitions
    <span class="nav-badge">{{ \Modules\Procurement\Models\Requisition::where('status', 'pending')->count() }}</span>
  </a>

  <a href="{{ route('procurement.deliveries') }}" class="nav-item {{ request()->routeIs('procurement.deliveries') ? 'active' : '' }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><circle cx="7" cy="18" r="2" stroke="currentColor" stroke-width="2"/><circle cx="17" cy="18" r="2" stroke="currentColor" stroke-width="2"/></svg>
    Deliveries
    <span class="nav-badge blue">{{ \Modules\Procurement\Models\Delivery::whereIn('status', ['pending', 'intransit'])->count() }}</span>
  </a>

</aside>

