<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nexora Shipping</title>
<style>
  :root {
    --bg-header: #0B1E3D;
    --bg-dark: #1B3A6B;
    --bg-card: #0B1E3D;
    --text-light: #FFFFFF;
    --text-muted: #9FB3D1;
    --border-soft: rgba(255,255,255,0.08);
    --accent: #3B82F6;
    --pill: #16305c;
    --pill-border: #2c4373;
  }

  * { box-sizing: border-box; }

  body {
    margin: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: var(--bg-dark);
    color: var(--text-light);
  }

  /* ===== Navbar ===== */
  .navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 40px;
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-soft);
  }

.brand{
    display:flex;
    align-items:center;
    gap:14px;
}

.logout-logo{
    display:flex;
    align-items:center;
    gap:14px;
    text-decoration:none;
    color:inherit;
    cursor:pointer;
    transition:
        transform .25s ease,
        filter .25s ease;
}

.logout-logo:hover{
    transform:scale(1.06);
    filter:drop-shadow(0 8px 18px rgba(59,130,246,.45));
}

.logout-logo:active{
    transform:scale(.96);
}

.logout-logo:visited,
.logout-logo:link,
.logout-logo:hover,
.logout-logo:active{
    color:inherit;
}

.logout-logo .title{
    color:#FFFFFF;
}

.logout-logo .subtitle{
    color:#3B82F6;
}

  .logo {
    width: 46px;
    height: 50px;
    object-fit: contain;
  }

  .brand-text .title { font-size: 20px; font-weight: 700; letter-spacing: 1px; }
  .brand-text .subtitle { font-size: 11px; color: #3B82F6; letter-spacing: 1px; }

  .nav-links { display: flex; gap: 36px; }
  .nav-links a { color: var(--text-muted); text-decoration: none; font-size: 15px; font-weight: 500; }
  .nav-links a.active { color: var(--text-light); font-weight: 700; }

  .nav-links a:hover {
    color: var(--text-light);
    text-shadow: 0 0 0.4px currentColor, 0 0 0.4px currentColor;
  }

  /* ===== Stats Row ===== */
  .stats-row {
    display: flex;
    gap: 24px;
    padding: 32px 40px 10px;
    flex-wrap: wrap;
  }

  .stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-soft);
    border-radius: 12px;
    padding: 22px 28px;
    flex: 1;
    min-width: 200px;
  }

  .stat-card .label { color: var(--text-muted); font-size: 14px; font-weight: 600; margin-bottom: 10px; }
  .stat-card .value { font-size: 32px; font-weight: 700; }

  /* ---------- Main Content ---------- */
  .content {
    display: flex;
    gap: 24px;
    padding: 28px 40px 60px 40px;
  }

  .panel {
    background: var(--bg-card);
    border-radius: 12px;
    overflow: hidden;
  }

  .order-queue {
    flex: 2.5;
    display: flex;
    flex-direction: column;
    height: 560px;
  }
  .activity {
    flex: 1;
    display: flex;
    flex-direction: column;
    height: 560px;
  }

  /* Scrollable body under the fixed panel header */
  .table-scroll {
    flex: 1;
    overflow-y: auto;
  }

  .table-scroll::-webkit-scrollbar {
    width: 8px;
  }
  .table-scroll::-webkit-scrollbar-track {
    background: transparent;
  }
  .table-scroll::-webkit-scrollbar-thumb {
    background: var(--pill-border);
    border-radius: 8px;
  }
  .table-scroll::-webkit-scrollbar-thumb:hover {
    background: var(--accent);
  }

  /* Keep column headers pinned while rows scroll */
  .order-queue thead th {
    position: sticky;
    top: 0;
    background: var(--bg-card);
    z-index: 5;
  }

  .panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    position: relative;
    gap: 16px;
  }

  .panel-header .title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    font-size: 16px;
    white-space: nowrap;
  }

  .panel-header .actions {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-muted);
    font-size: 14px;
  }

  /* ===== Search + Filter (working controls) ===== */
  .search-wrap {
    position: relative;
  }

  .search-wrap input {
    width: 170px;
    background: var(--pill);
    border: 1px solid var(--pill-border);
    border-radius: 20px;
    padding: 8px 14px 8px 32px;
    color: var(--text-light);
    font-size: 13px;
    outline: none;
    transition: width 0.15s ease, border-color 0.15s ease;
  }

  .search-wrap input:focus {
    width: 210px;
    border-color: var(--accent);
  }

  .search-wrap input::placeholder {
    color: var(--text-muted);
  }

  .search-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    pointer-events: none;
    font-size: 12px;
  }

  .filter-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--pill);
    border: 1px solid var(--pill-border);
    border-radius: 20px;
    padding: 8px 14px;
    color: var(--text-light);
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    position: relative;
  }

  .filter-btn:hover,
  .filter-btn.active {
    border-color: var(--accent);
  }

  .filter-btn .caret {
    font-size: 10px;
    color: var(--text-muted);
    transition: transform 0.15s ease;
  }

  .filter-btn.open .caret {
    transform: rotate(180deg);
  }

  .filter-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #ff2f92;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 10px;
    line-height: 1.4;
    display: none;
  }

  .filter-panel {
    position: absolute;
    right: 24px;
    top: 56px;
    background: #16305c;
    border: 1px solid var(--pill-border);
    border-radius: 12px;
    padding: 14px 16px;
    width: 200px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.5);
    display: none;
    z-index: 30;
  }

  .filter-panel.show {
    display: block;
  }

  .filter-panel .filter-title {
    color: var(--text-muted);
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 10px;
  }

  .filter-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 0;
    cursor: pointer;
    color: var(--text-light);
    font-size: 14px;
    font-weight: 600;
    user-select: none;
  }

  .filter-option input {
    width: 16px;
    height: 16px;
    accent-color: var(--accent);
    cursor: pointer;
  }

  .filter-overlay {
    position: fixed;
    inset: 0;
    z-index: 20;
    display: none;
  }

  .filter-overlay.show {
    display: block;
  }

  .no-results-row td {
    text-align: center;
    padding: 30px;
    color: var(--text-muted);
    font-size: 14px;
  }
  /* ===== end search + filter ===== */

  table { width: 100%; border-collapse: collapse; }

  thead th {
    text-align: left;
    padding: 14px 24px;
    font-size: 14px;
    color: #fff;
    border-bottom: 1px solid rgba(255,255,255,0.08);
  }

  tbody td { padding: 14px 24px; font-size: 14px; border-bottom: 1px solid rgba(255,255,255,0.05); }
  tbody tr:nth-child(even) { background: rgba(255,255,255,0.02); }

  .order-id, .product { color: var(--text-muted); }
  .customer { font-weight: 600; }

  .shipping-row { cursor: pointer; transition: background 0.15s ease; }
  .shipping-row:hover { background: rgba(255,255,255,0.04); }

  .status-tag {
    display: inline-block;
    font-weight: 700;
    font-size: 11px;
    padding: 3px 10px;
    border-radius: 12px;
  }

  .status-tag.tag-packing   { background: #6B4A1E; color: #FBD38D; }
  .status-tag.tag-shipped   { background: #1E5A6B; color: #7DD3E8; }
  .status-tag.tag-transit   { background: #1E3A6B; color: #93C5FD; }
  .status-tag.tag-delivered { background: #1E5A3A; color: #86EFAC; }
  .status-tag.tag-cancelled { background: #4A1E1E; color: #F3A9A9; }

  .btn-prepare {
    display: inline-block;
    background: var(--bg-dark);
    color: var(--text-light);
    font-weight: 700;
    font-size: 13px;
    padding: 6px 14px;
    border-radius: 20px;
    text-align: center;
    border: none;
    cursor: pointer;
  }

  .btn-prepare:hover { background: #244a80; }

  .empty-row td { height: 38px; }

  /* Delivery alerts */
  .activity-list {
    flex: 1;
    overflow-y: auto;
    padding: 8px 0;
  }

  .activity-list::-webkit-scrollbar {
    width: 8px;
  }
  .activity-list::-webkit-scrollbar-track {
    background: transparent;
  }
  .activity-list::-webkit-scrollbar-thumb {
    background: var(--pill-border);
    border-radius: 8px;
  }
  .activity-list::-webkit-scrollbar-thumb:hover {
    background: var(--accent);
  }

  .activity-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px 24px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    font-size: 14px;
  }

  .activity-item:last-child { border-bottom: none; }
  .activity-icon { width: 18px; text-align: center; flex-shrink: 0; margin-top: 2px; }

  /* ============================================
     Blur + modal mechanism
     ============================================ */
  #pageContent {
    transition: filter 0.25s ease;
  }

  #pageContent.blurred {
    filter: blur(4px);
  }

  .overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(5, 12, 28, 0.45);
    align-items: center;
    justify-content: center;
    z-index: 100;
  }

  .overlay.active { display: flex; }

  .modal {
    width: 620px;
    max-width: 90vw;
    background: #16305c;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.4);
  }

  .modal-header { background: #0f2549; padding: 20px 28px; }
  .modal-header h2 { margin: 0; color: #fff; font-size: 18px; }
  .modal-header p { margin: 4px 0 0; color: #8ea3cc; font-size: 13px; }

  .modal-body {
    padding: 24px 28px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px 20px;
  }

  .modal-body .field-label { margin: 0 0 6px; font-size: 12px; color: #8ea3cc; }
  .modal-body .field-value { margin: 0; font-size: 15px; color: #fff; font-weight: 600; }

  .modal-body .status-pill {
    display: inline-block;
    font-weight: 700;
    font-size: 13px;
    padding: 4px 12px;
    border-radius: 12px;
    background: #1E5A6B;
    color: #7DD3E8;
  }

  .modal-body .status-pill.tag-packing   { background: #6B4A1E; color: #FBD38D; }
  .modal-body .status-pill.tag-shipped   { background: #1E5A6B; color: #7DD3E8; }
  .modal-body .status-pill.tag-transit   { background: #1E3A6B; color: #93C5FD; }
  .modal-body .status-pill.tag-delivered { background: #1E5A3A; color: #86EFAC; }
  .modal-body .status-pill.tag-cancelled { background: #4A1E1E; color: #F3A9A9; }

  .assign-banner {
    margin: 0 28px 20px;
    background: #3a3016;
    border: 1px solid #6b5a24;
    border-radius: 8px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    color: #f3d98a;
    font-size: 13px;
  }

  .assign-banner.hidden { display: none; }

  .btn-assign-driver {
    background: #6B4A1E;
    color: #FBD38D;
    border: none;
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
  }

  .btn-assign-driver:hover { background: #7d5824; }

  .modal-footer {
    display: flex;
    gap: 12px;
    padding: 20px 28px;
    border-top: 1px solid rgba(255,255,255,0.08);
  }

  .btn {
    flex: 1;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
  }

  .btn-close { background: #2b4a7c; color: #dbe4f5; }
  .btn-close:hover { background: #345a94; }

  .btn-cancel { background: #7a2340; color: #f9c3d3; }
  .btn-cancel:hover { background: #8f2a4b; }

  /* ============================================
     Driver selection modal
     ============================================ */
  .driver-modal {
    width: 460px;
  }

  .driver-modal .modal-body {
    display: block;
    padding: 22px 28px 6px;
  }

  .driver-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .driver-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #0f2549;
    border: 1px solid var(--pill-border);
    border-radius: 10px;
    padding: 14px 16px;
    cursor: pointer;
    transition: border-color 0.15s ease, background 0.15s ease;
  }

  .driver-card:hover { border-color: var(--accent); }

  .driver-card.selected {
    border-color: #7c5cff;
    background: rgba(124, 92, 255, 0.12);
  }

  .driver-name { font-weight: 700; font-size: 14.5px; margin-bottom: 3px; }
  .driver-sub { color: var(--text-muted); font-size: 12.5px; }

  .driver-avail {
    background: rgba(34, 197, 94, 0.18);
    color: #4ade80;
    font-size: 12px;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 20px;
    white-space: nowrap;
  }

  .driver-avail.busy {
    background: rgba(148, 163, 184, 0.15);
    color: #94a3b8;
  }

  .btn-back { background: #2b4a7c; color: #dbe4f5; }
  .btn-back:hover { background: #345a94; }

  .btn-confirm { background: #5b4de0; color: #fff; }
  .btn-confirm:hover { background: #6c5cf0; }
  .btn-confirm:disabled {
    background: #33436e;
    color: #7d8bb0;
    cursor: not-allowed;
  }

  .assign-toast {
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%) translateY(20px);
    background: #22c55e;
    color: #08240f;
    font-weight: 700;
    font-size: 14px;
    padding: 12px 22px;
    border-radius: 8px;
    opacity: 0;
    transition: opacity 0.25s ease, transform 0.25s ease;
    z-index: 200;
    pointer-events: none;
  }

  .assign-toast.show {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
  }
</style>
</head>
<body>

  <div class="top-strip"></div>

  <!-- ============================================
       Everything the user should see BLURRED while
       the modal is open goes inside #pageContent.
       ============================================ -->
       
  <div id="pageContent">

    <!-- Navbar -->
    <div class="navbar">
      <a href="{{ route('order-fulfillment.logout') }}" class="brand logout-logo" title="Logout">
    <img class="logo" src="{{ asset('logo/Nexora_Logo_Transparent.png') }}" alt="Nexora Logo">
    <div class="brand-text">
        <div class="title">NEXORA</div>
        <div class="subtitle">ENTERPRISE RESOURCE PLANNING</div>
    </div>
</a>
      <div class="nav-links">
      <a href="{{ route('order-fulfillment.dashboard') }}">Dashboard</a>
      <a href="{{ route('order-fulfillment.orders') }}">Orders</a>
      <a href="{{ route('order-fulfillment.packing') }}">Packing</a>
      <a href="{{ route('order-fulfillment.shipping') }}" class="active">Shipping</a>
      <a href="{{ route('order-fulfillment.return') }}">Returns</a>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="label">Shipped today</div>
        <div class="value">{{ $shippedToday }}</div>
      </div>
      <div class="stat-card">
        <div class="label">In transit</div>
        <div class="value">{{ $inTransit }}</div>
      </div>
      <div class="stat-card">
        <div class="label">On time delivery rate</div>
        <div class="value">{{ $onTimeRate }}%</div>
      </div>
      <div class="stat-card">
        <div class="label">Delayed shipment</div>
        <div class="value">{{ $delayed }}</div>
      </div>
    </div>

    <section class="content">

      <div class="panel order-queue">
        <div class="panel-header">
          <div class="title">Ã°Å¸â€œÂ¦ Shipment tracking</div>
          <div class="actions">
            <div class="search-wrap">
              <span class="search-icon">Ã°Å¸â€Â</span>
              <input type="text" id="shippingSearch" placeholder="Search..." autocomplete="off">
            </div>

            <button id="filterBtn" class="filter-btn">
              Filter <span class="caret">Ã¢â€“Â¾</span>
              <span id="filterBadge" class="filter-badge">1</span>
            </button>

            <div id="filterPanel" class="filter-panel">
              <div class="filter-title">Status</div>
              <label class="filter-option">
                <input type="radio" name="statusFilter" value="" class="status-check" checked>
                All
              </label>
              <label class="filter-option">
                <input type="radio" name="statusFilter" value="READY_TO_SHIP" class="status-check">
                READY FOR DELIVERY
              </label>
              <label class="filter-option">
                <input type="radio" name="statusFilter" value="SHIPPED" class="status-check">
                SHIPPED
              </label>
              <label class="filter-option">
                <input type="radio" name="statusFilter" value="OUT_FOR_DELIVERY" class="status-check">
                OUT FOR DELIVERY
              </label>
              <label class="filter-option">
                <input type="radio" name="statusFilter" value="DELIVERED" class="status-check">
                DELIVERED
              </label>
              <label class="filter-option">
                <input type="radio" name="statusFilter" value="DELAYED" class="status-check">
                DELAYED
              </label>
            </div>
          </div>
        </div>
        <div class="table-scroll">
        <table>
          <thead>
            <tr>
              <th>Order Id</th>
              <th>Customer</th>
              <th>Product</th>
              <th>Tracking no.</th>
              <th class="th-status">Status</th>
              <th>Destination</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="shippingTableBody">
            @foreach($shipments as $shipment)
@php
    $statusRaw = strtoupper($shipment->status);
    $statusLabels = [
        'SHIPPED'           => 'SHIPPED',
        'READY_TO_SHIP'     => 'READY FOR DELIVERY',
        'OUT_FOR_DELIVERY'  => 'OUT FOR DELIVERY',
        'DELIVERED'         => 'DELIVERED',
        'DELAYED'           => 'DELAYED',
    ];
    $statusLabel = $statusLabels[$statusRaw] ?? strtoupper(str_replace('_', ' ', $statusRaw));
    $statusClassMap = [
        'SHIPPED'           => 'tag-shipped',
        'READY_TO_SHIP'     => 'tag-packing',
        'OUT_FOR_DELIVERY'  => 'tag-transit',
        'DELIVERED'         => 'tag-delivered',
        'DELAYED'           => 'tag-cancelled',
    ];
    $statusClass = $statusClassMap[$statusRaw] ?? 'tag-shipped';
@endphp
<tr
    class="shipping-row"
    data-id="{{ $shipment->shipment_id }}"
    data-customer="{{ $shipment->customer_name }}"
    data-product="{{ $shipment->product_name }}"
    data-tracking="{{ $shipment->tracking_number }}"
    data-status="{{ $statusRaw }}"
    data-destination="{{ $shipment->address }}"
    data-amount="{{ number_format($shipment->amount, 2) }}"
    onclick="openShippingModal('{{ $shipment->shipment_id }}')"
>

    <td class="order-id">{{ $shipment->shipment_id }}</td>
    <td class="customer">{{ $shipment->customer_name }}</td>
    <td class="product">{{ $shipment->product_name }}</td>
    <td class="tracking">{{ $shipment->tracking_number }}</td>

    <td class="status-cell">
        <span class="status-tag {{ $statusClass }}">{{ $statusLabel }}</span>
    </td>

    <td>{{ $shipment->address }}</td>

    <td>
      @if($statusRaw === 'READY_TO_SHIP')
        <button
            class="btn-prepare"
            onclick="event.stopPropagation(); openShippingModal('{{ $shipment->shipment_id }}', true)">
            Assign Driver
        </button>
        @endif
    </td>

</tr>
@endforeach

            <tr class="no-results-row" id="noResultsRow" style="display:none;">
              <td colspan="7">No shipments match your search or filter.</td>
            </tr>
          </tbody>
        </table>
        </div>
      </div>

      <div class="panel activity">
        <div class="panel-header">
          <div class="title">Ã°Å¸â€â€ Delivery alerts</div>
        </div>
        <div class="activity-list">
          <div class="activity-item">
            <span class="activity-icon"></span>
          </div>
          <div class="activity-item">
            <span class="activity-icon"></span>
          </div>
          <div class="activity-item">
            <span class="activity-icon"></span>
          </div>
        </div>
      </div>

    </section>
  </div>

  <!-- ============================================
       Modals live OUTSIDE #pageContent so they never
       get blurred themselves.
       ============================================ -->

  <!-- Order detail modal -->
  <div class="overlay" id="packingOverlay">
    <div class="modal">
      <div class="modal-header">
        <h2 id="modalOrderId">Ã¢â‚¬â€</h2>
        <p>Website order</p>
      </div>

      <div class="modal-body">
        <div>
          <p class="field-label">Customer</p>
          <p class="field-value" id="modalCustomer">Ã¢â‚¬â€</p>
        </div>
        <div>
          <p class="field-label">Status</p>
          <span class="status-pill tag-packing" id="modalStatus">Ã¢â‚¬â€</span>
        </div>
        <div>
          <p class="field-label">Product</p>
          <p class="field-value" id="modalItem">Ã¢â‚¬â€</p>
        </div>
        <div>
          <p class="field-label">Tracing no.</p>
          <p class="field-value" id="modalTracking">Ã¢â‚¬â€</p>
        </div>
        <div>
          <p class="field-label">Courier</p>
          <p class="field-value" id="modalCourier">Ã¢â‚¬â€</p>
        </div>
        <div>
          <p class="field-label">Amount</p>
          <p class="field-value" id="modalAmount">Ã¢â‚¬â€</p>
        </div>
        <div>
          <p class="field-label">Due date</p>
          <p class="field-value" id="modalDue">Ã¢â‚¬â€</p>
        </div>
        <div style="grid-column: 1 / -1;">
          <p class="field-label">Delivery Address</p>
          <p class="field-value" id="modalAddress">Ã¢â‚¬â€</p>
        </div>
      </div>

      <div class="assign-banner" id="assignBanner">
        <span>This order is ready for delivery. Assign a driver to begin the final leg.</span>
        <button class="btn-assign-driver" onclick="assignDriver()">Assign driver</button>
      </div>

      <div class="modal-footer">
        <button class="btn btn-close" onclick="closePackingModal()">Close</button>
        <button class="btn btn-cancel">Cancel order</button>
      </div>
    </div>
  </div>

  <!-- Driver selection modal -->
  <div class="overlay" id="driverOverlay">
    <div class="modal driver-modal">
      <div class="modal-header">
        <h2 id="driverModalOrderId">#ORD-4821</h2>
        <p>Website order</p>
      </div>

      <div class="modal-body">
        <div class="driver-list" id="driverList">
          <!-- driver cards injected by JS -->
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-back" onclick="backToOrderModal()">Back</button>
        <button class="btn btn-confirm" id="confirmAssignBtn" onclick="confirmDriverAssignment()" disabled>Confirm Assignment</button>
      </div>
    </div>
  </div>

  <div class="filter-overlay" id="filterOverlay"></div>

  <div class="assign-toast" id="assignToast">Driver assigned successfully</div>

  <script>

    // Base URL for the shipping endpoints, resolved server-side so it
    // works no matter where this app is actually mounted (e.g. served
    // from a subfolder like /dashboard/OrderFullfillment/public rather
    // than the domain root).
    const SHIPPING_BASE_URL = "{{ url('/order-fulfillment/shipping') }}";

    const orders = @json($shipments->keyBy('shipment_id'));
    const statusLabels = {
      'SHIPPED': 'SHIPPED',
      'READY_TO_SHIP': 'READY FOR DELIVERY',
      'OUT_FOR_DELIVERY': 'OUT FOR DELIVERY',
      'DELIVERED': 'DELIVERED',
      'DELAYED': 'DELAYED',
    };
    const statusTagClasses = {
      'SHIPPED': 'tag-shipped',
      'READY_TO_SHIP': 'tag-packing',
      'OUT_FOR_DELIVERY': 'tag-transit',
      'DELIVERED': 'tag-delivered',
      'DELAYED': 'tag-cancelled',
    };
    const STATUS_TAG_CLASSES = ['tag-packing', 'tag-shipped', 'tag-transit', 'tag-delivered', 'tag-cancelled'];

    let currentOrderId = null;
    let selectedDriverId = null;

    function openShippingModal(orderId, showBanner) {
      const order = orders[orderId];
      if (order) {
        document.getElementById('modalOrderId').textContent = orderId;
        document.getElementById('modalCustomer').textContent = order.customer_name;
        document.getElementById('modalItem').textContent = order.product_name;
        document.getElementById('modalTracking').textContent = order.tracking_number;
        const modalStatusEl = document.getElementById('modalStatus');
        modalStatusEl.textContent = statusLabels[order.status] || order.status;
        modalStatusEl.classList.remove(...STATUS_TAG_CLASSES);
        modalStatusEl.classList.add(statusTagClasses[order.status] || 'tag-shipped');
        document.getElementById('modalCourier').textContent = order.courier;
        document.getElementById('modalDue').textContent = order.due_date;
        document.getElementById('modalAddress').textContent = order.address;

        // Amount may come from the shipment JSON, or fall back to the
        // clicked row's data-amount attribute if the JSON doesn't have it.
        const rowEl = document.querySelector('.shipping-row[data-id="' + orderId + '"]');
        const rawAmount = order.amount ?? (rowEl ? rowEl.dataset.amount : null);
        document.getElementById('modalAmount').textContent =
          rawAmount != null ? 'Ã¢â€šÂ±' + rawAmount : 'Ã¢â‚¬â€';
      }

      // Only reveal the yellow "assign a driver" banner when the modal was
      // opened via the Assign Driver button Ã¢â‚¬â€ not from a plain row click.
      document.getElementById('assignBanner').classList.toggle('hidden', !showBanner);

      currentOrderId = orderId;
      document.getElementById('pageContent').classList.add('blurred');
      document.getElementById('packingOverlay').classList.add('active');
    }

    function closePackingModal() {
      document.getElementById('pageContent').classList.remove('blurred');
      document.getElementById('packingOverlay').classList.remove('active');
      currentOrderId = null;
    }

    function assignDriver() {
      // Swap the order modal for the driver-selection modal.
      // Background stays blurred the whole time.
      document.getElementById('packingOverlay').classList.remove('active');
      document.getElementById('driverModalOrderId').textContent =
        document.getElementById('modalOrderId').textContent;

      document.getElementById('driverOverlay').classList.add('active');
      fetchDrivers(currentOrderId);
    }

    async function fetchDrivers(orderId) {
      const list = document.getElementById('driverList');
      selectedDriverId = null;
      document.getElementById('confirmAssignBtn').disabled = true;
      list.innerHTML = '<p style="color: var(--text-muted); padding: 8px 4px;">Loading available driversÃ¢â‚¬Â¦</p>';

      try {
        const res = await fetch(`${SHIPPING_BASE_URL}/${orderId}/drivers`, {
          headers: { 'Accept': 'application/json' }
        });

        if (!res.ok) {
          // Show the real status/body instead of swallowing it, so the
          // actual cause (missing table, bad route, etc.) is visible.
          let detail = res.status + ' ' + res.statusText;
          try {
            const body = await res.json();
            if (body.message) detail = body.message;
          } catch (_) {}
          console.error('Failed to load drivers:', detail);
          throw new Error(detail);
        }

        const availableDrivers = await res.json();
        renderDriverList(availableDrivers);
      } catch (err) {
        list.innerHTML = '<p style="color:#f87171; padding:8px 4px;">Could not load drivers: ' + err.message + '</p>';
      }
    }

    function renderDriverList(availableDrivers) {
      const list = document.getElementById('driverList');
      list.innerHTML = '';

      if (!availableDrivers.length) {
        list.innerHTML = '<p style="color: var(--text-muted); padding: 8px 4px;">No available drivers for this courier right now.</p>';
        return;
      }

      availableDrivers.forEach(function (driver) {
        const card = document.createElement('div');
        card.className = 'driver-card';
        card.innerHTML = `
          <div>
            <div class="driver-name">${driver.name}</div>
            <div class="driver-sub">${driver.vehicle_type} Ã‚Â· Plate ${driver.plate_number}</div>
          </div>
          <span class="driver-avail">Available</span>
        `;

        card.addEventListener('click', function () {
          document.querySelectorAll('.driver-card').forEach(c => c.classList.remove('selected'));
          card.classList.add('selected');
          selectedDriverId = driver.id;
          document.getElementById('confirmAssignBtn').disabled = false;
        });

        list.appendChild(card);
      });
    }

    function backToOrderModal() {
      document.getElementById('driverOverlay').classList.remove('active');
      document.getElementById('packingOverlay').classList.add('active');
    }

    async function confirmDriverAssignment() {
      if (!selectedDriverId || !currentOrderId) return;

      const confirmBtn = document.getElementById('confirmAssignBtn');
      confirmBtn.disabled = true;
      confirmBtn.textContent = 'AssigningÃ¢â‚¬Â¦';

      try {
        const res = await fetch(`${SHIPPING_BASE_URL}/${currentOrderId}/assign-driver`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ driver_id: selectedDriverId })
        });

        const data = await res.json();

        if (!res.ok) {
          showAssignToast(data.message || 'Could not assign driver.', true);
          confirmBtn.disabled = false;
          confirmBtn.textContent = 'Confirm Assignment';
          return;
        }

        applyAssignmentToRow(currentOrderId, data.status);

        document.getElementById('driverOverlay').classList.remove('active');
        document.getElementById('pageContent').classList.remove('blurred');

        showAssignToast(data.message);

        currentOrderId = null;
        selectedDriverId = null;
      } catch (err) {
        showAssignToast('Network error Ã¢â‚¬â€ please try again.', true);
      } finally {
        confirmBtn.disabled = false;
        confirmBtn.textContent = 'Confirm Assignment';
      }
    }

    // Reflect the new status on the table row immediately, without a full
    // page reload: swap the status pill and drop the now-irrelevant
    // "Assign Driver" button.
    function applyAssignmentToRow(orderId, newStatus) {
      if (orders[orderId]) orders[orderId].status = newStatus;

      const row = document.querySelector('.shipping-row[data-id="' + orderId + '"]');
      if (!row) return;

      row.dataset.status = newStatus;

      const tag = row.querySelector('.status-cell .status-tag');
      if (tag) {
        tag.textContent = statusLabels[newStatus] || newStatus;
        tag.classList.remove(...STATUS_TAG_CLASSES);
        tag.classList.add(statusTagClasses[newStatus] || 'tag-shipped');
      }

      const actionCell = row.querySelector('td:last-child');
      if (actionCell) actionCell.innerHTML = '';
    }

    function showAssignToast(message, isError = false) {
      const toast = document.getElementById('assignToast');
      toast.textContent = message;
      toast.style.background = isError ? '#ef4444' : '#22c55e';
      toast.style.color = isError ? '#ffffff' : '#08240f';
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 2600);
    }

    // Click outside either modal (on the dim backdrop) to close everything
    ['packingOverlay', 'driverOverlay'].forEach(function (id) {
      document.getElementById(id).addEventListener('click', function (e) {
        if (e.target.id === id) {
          document.getElementById('packingOverlay').classList.remove('active');
          document.getElementById('driverOverlay').classList.remove('active');
          document.getElementById('pageContent').classList.remove('blurred');
          currentOrderId = null;
          selectedDriverId = null;
        }
      });
    });

    /* ===================== Search + Filter (working) ===================== */
    const shippingRows   = Array.from(document.querySelectorAll('.shipping-row'));
    const searchInput    = document.getElementById('shippingSearch');
    const filterBtn      = document.getElementById('filterBtn');
    const filterPanel    = document.getElementById('filterPanel');
    const filterOverlay  = document.getElementById('filterOverlay');
    const filterBadge    = document.getElementById('filterBadge');
    const noResultsRow   = document.getElementById('noResultsRow');
    const statusChecks   = document.querySelectorAll('.status-check');

    function activeStatus() {
      const checked = Array.from(statusChecks).find(c => c.checked);
      return checked ? checked.value : '';
    }

    function applyShippingFilters() {
      const query = searchInput.value.trim().toLowerCase();
      const active = activeStatus();
      let visibleCount = 0;

      shippingRows.forEach(function (row) {
        const d = row.dataset;
        const haystack = [d.id, d.customer, d.product, d.tracking, d.status, d.destination]
          .join(' ')
          .toLowerCase();

        const matchesSearch = query === '' || haystack.includes(query);
        const matchesStatus = active === '' || d.status === active;
        const visible = matchesSearch && matchesStatus;

        row.style.display = visible ? '' : 'none';
        if (visible) visibleCount++;
      });

      noResultsRow.style.display = visibleCount === 0 ? '' : 'none';

      if (active !== '') {
        filterBtn.classList.add('active');
        filterBadge.style.display = 'inline-block';
        filterBadge.textContent = '1';
      } else {
        filterBtn.classList.remove('active');
        filterBadge.style.display = 'none';
      }
    }

    function openFilterPanel() {
      filterPanel.classList.add('show');
      filterOverlay.classList.add('show');
      filterBtn.classList.add('open');
    }

    function closeFilterPanel() {
      filterPanel.classList.remove('show');
      filterOverlay.classList.remove('show');
      filterBtn.classList.remove('open');
    }

    filterBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      filterPanel.classList.contains('show') ? closeFilterPanel() : openFilterPanel();
    });

    filterOverlay.addEventListener('click', closeFilterPanel);

    statusChecks.forEach(function (c) {
      c.addEventListener('change', applyShippingFilters);
    });

    searchInput.addEventListener('input', applyShippingFilters);
    /* =================== end Search + Filter =================== */
  </script>

</body>
</html>
