<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Nexora Orders</title>
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

  .brand-text .title {
    font-size: 20px;
    font-weight: 700;
    letter-spacing: 1px;
  }

  .brand-text .subtitle {
    font-size: 11px;
    color: #3B82F6;
    letter-spacing: 1px;
  }

  .nav-links {
    display: flex;
    gap: 36px;
  }

  .nav-links a {
    color: var(--text-muted);
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
  }

  .nav-links a.active {
    color: var(--text-light);
    font-weight: 700;
  }

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

  .stat-card .label {
    color: var(--text-muted);
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 10px;
  }

  .stat-card .value {
    font-size: 32px;
    font-weight: 700;
  }

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
    /* Fixed frame: panel height never grows past this, queue scrolls inside it */
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
    width: 180px;
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

  table {
    width: 100%;
    border-collapse: collapse;
    background: var(--bg-card);
  }

  thead th {
    text-align: left;
    padding: 12px 20px;
    font-size: 12px;
    color: #8b94b8;
    font-weight: 600;
    border-bottom: 1px solid rgba(255,255,255,0.08);
  }

  tbody tr {
    border-bottom: 1px solid rgba(255,255,255,0.06);
    transition: background 0.15s ease;
  }

  tbody tr:last-child {
    border-bottom: none;
  }

  tbody tr:nth-child(even) { background: rgba(255,255,255,0.02); }

  tbody tr:hover {
    background: rgba(255,255,255,0.04);
    cursor: pointer;
  }

  td {
    padding: 14px 20px;
    font-size: 14px;
    color: #cdd6f5;
    vertical-align: middle;
  }

  td.order-id {
    color: #8b94b8;
  }

  .th-qty, .qty-cell,
  .th-status, .status-cell,
  .th-priority, .priority-cell {
    text-align: center;
  }

  td.customer {
    color: #f1f3fb;
    font-weight: 700;
  }

  .badge {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 4px;
  }

  .badge.status {
    padding: 3px 10px;
    border-radius: 12px;
    background: rgba(255,255,255,0.1);
    color: #9FB3D1;
  }

  .badge.status.status-new { background: rgba(255,255,255,0.1); color: #9FB3D1; }
  .badge.status.status-packing { background: #6B4A1E; color: #FBD38D; }
  .badge.status.status-shipped { background: #1E5A6B; color: #7DD3E8; }
  .badge.status.status-delivered { background: #1E5A3A; color: #86EFAC; }
  .badge.status.status-cancelled { background: #4A1E1E; color: #F3A9A9; }

  .badge.priority {
    background: #6e3a63;
    color: #e7c9e0;
  }

  .badge.priority2 {
    background: #6B4A1E;
    color: #FBD38D;
  }

  .prepare-btn, .btn-prepare {
    background: var(--bg-dark);
    color: var(--text-light);
    border: none;
    padding:4px 10px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: inline-block;
  }

  .empty-row td {
    height: 38px;
  }

  /* Recent Activity */
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

  .activity-icon {
    width: 18px;
    text-align: center;
    flex-shrink: 0;
    margin-top: 2px;
  }

  .icon-cart { color: #5C9AE0; }
  .icon-truck { color: #5C9AE0; }
  .icon-warn { color: #E0735C; }

  .activity-empty {
    height: 50px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
  }

  /* Blur + modal mechanism */
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

  .overlay.active {
    display: flex;
  }

  .modal {
    width: 480px;
    max-width: 90vw;
    background: #16305c;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.4);
  }

  .modal-header {
    background: #0f2549;
    padding: 20px 28px;
  }

  .modal-header h2 {
    margin: 0;
    color: #fff;
    font-size: 18px;
  }

  .modal-header p {
    margin: 4px 0 0;
    color: #8ea3cc;
    font-size: 13px;
  }

  .modal-body {
    padding: 24px 28px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px 20px;
  }

  .modal-body .field-label {
    margin: 0 0 6px;
    font-size: 12px;
    color: #8ea3cc;
  }

  .modal-body .field-value {
    margin: 0;
    font-size: 15px;
    color: #fff;
    font-weight: 600;
  }

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

  .btn-close {
    background: #2b4a7c;
    color: #dbe4f5;
  }

  .btn-close:hover {
    background: #345a94;
  }

  .btn-cancel {
    background: #7a2340;
    color: #f9c3d3;
  }

  .btn-cancel:hover {
    background: #8f2a4b;
  }
  .priority-high {
    background: #dc3545;
    color: #fff;
}

.priority-medium {
    background: #ffc107;
    color: #000;
}

.priority-low {
    background: #6B2B2B;
    color: #fff;
}

.priority-new {
    background: #6c757d;
    color: #fff;
}

.priority-new {
    background: #6c757d;
    color: #fff;
}

.confirm-box {
    background: rgba(225,75,90,0.16);
    border: 1px solid rgba(225,75,90,0.45);
    border-radius: 10px;
    padding: 12px 14px;
    margin: 0 28px 20px;
    display: none;
}
.confirm-box.show { display: block; }
.confirm-box p {
    margin: 0 0 10px;
    font-size: 12.5px;
    color: #ffd9dd;
    line-height: 1.4;
}
.confirm-actions { display: flex; gap: 10px; }
.confirm-actions button {
    flex: 1;
    border: none;
    border-radius: 7px;
    padding: 8px 0;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}
.btn-yes { background: #e14b5a; color: #fff; }
.btn-no  { background: #2b4a7c; color: #dbe4f5; }
.btn-cancel.disabled { opacity: .4; cursor: not-allowed; }

.priority-cancelled {
    background: #dc3545;
    color: #fff;
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
     <a href="{{ route('order-fulfillment.orders') }}" class="active">Orders</a>
      <a href="{{ route('order-fulfillment.packing') }}">Packing</a>
      <a href="{{ route('order-fulfillment.shipping') }}">Shipping</a>
      <a href="{{ route('order-fulfillment.return') }}">Returns</a>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="label">Total order today</div>
        <div class="value">{{ $ordersToday }}</div>
      </div>
      <div class="stat-card">
        <div class="label">In progress</div>
        <div class="value">{{ $inPacking }}</div>
      </div>
      <div class="stat-card">
        <div class="label">Unfulfilled</div>
        <div class="value">{{ $shippedToday }}</div>
      </div>
      <div class="stat-card">
        <div class="label">Fulfilled today</div>
        <div class="value">{{ $onTimeRate }}</div>
      </div>
    </div>

    <section class="content">

      <div class="panel order-queue">
        <div class="panel-header">
          <div class="title">Ã°Å¸â€œÂ¦ Order queue</div>
          <div class="actions">
            <div class="search-wrap">
              <span class="search-icon">Ã°Å¸â€Â</span>
              <input type="text" id="orderSearch" placeholder="Search..." autocomplete="off">
            </div>

            <button id="filterBtn" class="filter-btn">
              Filter <span class="caret">Ã¢â€“Â¾</span>
              <span id="filterBadge" class="filter-badge">1</span>
            </button>

            <div id="filterPanel" class="filter-panel">
              <div class="filter-title">Priority</div>
              <label class="filter-option">
                <input type="radio" name="priorityFilter" value="" class="priority-check" checked>
                All
              </label>
              <label class="filter-option">
                <input type="radio" name="priorityFilter" value="LOW" class="priority-check">
                Low
              </label>
              <label class="filter-option">
                <input type="radio" name="priorityFilter" value="MEDIUM" class="priority-check">
                Medium
              </label>
              <label class="filter-option">
                <input type="radio" name="priorityFilter" value="HIGH" class="priority-check">
                High
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
              <th class="th-qty">Qty</th>
              <th class="th-status">Status</th>
              <th class="th-priority">Priority</th>
              <th>Due</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="ordersTableBody">
            @forelse($orders as $order)
            @php
            $priority = \Modules\OrderFulfillment\Helpers\OrderPriority::order($order->created_at);
            $statusRaw = strtoupper($order->status);
            $statusClassMap = [
                'NEW'       => 'status-new',
                'PACKING'   => 'status-packing',
                'SHIPPED'   => 'status-shipped',
                'DELIVERED' => 'status-delivered',
                'CANCELLED' => 'status-cancelled',
            ];
            $statusClass = $statusClassMap[$statusRaw] ?? 'status-new';
            @endphp
            <tr class="order-row"
                style="cursor: pointer;"
                data-id="{{ $order->id }}"
                data-customer="{{ $order->customer_name }}"
                data-product="{{ $order->product_name }}"
                data-qty="{{ $order->qty }}"
                data-status="{{ $statusRaw }}"
                data-priority="{{ $priority['label'] }}"
                data-priority-class="{{ $priority['class'] }}"
                data-amount="{{ number_format($order->product_amount * $order->qty, 2) }}"
                data-due="{{ \Carbon\Carbon::parse($order->due_date)->format('M d') }}">
              <td class="order-id">{{ $order->id }}</td>
              <td class="customer">{{ $order->customer_name }}</td>
              <td>{{ $order->product_name }}</td>
              <td class="qty-cell">{{ $order->qty }}</td>
              <td class="status-cell"><span class="badge status {{ $statusClass }}">{{ $statusRaw }}</span></td>
              <td class="priority-cell">
              @if ($statusRaw !== 'CANCELLED')
              <span class="badge {{ $priority['class'] }}">
              {{ $priority['label'] }}
              </span>
              @endif
              </td>
              <td>{{ \Carbon\Carbon::parse($order->due_date)->format('M d') }}</td>
              <td>
                @if ($statusRaw === 'NEW')
                  <button type="button"
                          class="btn-prepare"
                          data-order-id="{{ $order->id }}"
                          onclick="event.stopPropagation(); prepareOrder('{{ $order->id }}', this)">
                    Prepare
                  </button>
                @endif
              </td>
            </tr>
            @empty
            <tr class="empty-row">
              <td colspan="8" style="text-align:center; padding:24px; color:#8b94b8;">No orders yet.</td>
            </tr>
            @endforelse

            {{-- Shown by JS when search/filter produce zero matches --}}
            <tr class="no-results-row" id="noResultsRow" style="display:none;">
              <td colspan="8">No orders match your search or filter.</td>
            </tr>
          </tbody>
        </table>
        </div>
      </div>

      <div class="panel activity">
        <div class="panel-header">
          <div class="title">Ã°Å¸â€œË† Recent activity</div>
        </div>
        <div class="activity-list">
          @forelse ($recentActivity as $order)
            <div class="activity-item">
              <span class="activity-icon">{{ $order->activity_icon }}</span>
              <span>{{ $order->activity_message }}</span>
            </div>
          @empty
            <div class="activity-empty" style="display:flex; align-items:center; justify-content:center; color:var(--text-muted); font-size:13px;">
              No recent activity.
            </div>
          @endforelse
        </div>
      </div>
    </section>

  </div>

  <div class="overlay" id="orderOverlay">
    <div class="modal">
      <div class="modal-header">
        <h2 id="modalOrderId">#ORD-4821</h2>
        <p>Website order</p>
      </div>
      <div class="modal-body">
        <div>
          <p class="field-label">Customer</p>
          <p class="field-value" id="modalCustomer">Maria Santos</p>
        </div>
        <div>
          <p class="field-label">Status</p>
          <span class="badge status status-new" id="modalStatus">NEW</span>
        </div>
        <div>
          <p class="field-label">Product</p>
          <p class="field-value" id="modalProduct">Wireless Headphone</p>
        </div>
        <div>
          <p class="field-label">Quantity</p>
          <p class="field-value" id="modalQty">2</p>
        </div>
        <div>
          <p class="field-label">Amount</p>
          <p class="field-value" id="modalAmount">Ã¢â€šÂ±0.00</p>
        </div>
        <div>
          <p class="field-label">Priority</p>
          <span class="badge priority" id="modalPriority">Low</span>
        </div>
        <div>
          <p class="field-label">Due date</p>
          <p class="field-value" id="modalDue">Jun 25</p>
        </div>
      </div>
      <div class="confirm-box" id="confirmBox">
        <p>Ã¢Å¡Â Ã¯Â¸Â Are you sure you want to cancel this order? This action can't be undone and the customer will be notified.</p>
        <div class="confirm-actions">
          <button class="btn-yes" id="yesCancelBtn">Yes, cancel the order</button>
          <button class="btn-no" id="noKeepBtn">No, keep order</button>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-close" onclick="closeOrderModal()">Close</button>
        <button class="btn btn-cancel" id="cancelOrderBtn">Cancel order</button>
      </div>
    </div>
  </div>
  

  <div class="filter-overlay" id="filterOverlay"></div>

  <script>
    const STATUS_CLASSES = ['status-new', 'status-packing', 'status-shipped', 'status-delivered', 'status-cancelled'];

    function statusToClass(status) {
      const map = {
        NEW: 'status-new',
        PACKING: 'status-packing',
        SHIPPED: 'status-shipped',
        DELIVERED: 'status-delivered',
        CANCELLED: 'status-cancelled',
      };
      return map[status] || 'status-new';
    }

    function setStatusBadge(el, status) {
      if (!el) return;
      el.textContent = status;
      el.classList.remove(...STATUS_CLASSES);
      el.classList.add(statusToClass(status));
    }

    const orderRows = Array.from(document.querySelectorAll('.order-row'));
    let currentOrderRow = null;

    orderRows.forEach(function (row) {
      row.addEventListener('click', function (e) {
        // If the click started on (or inside) a button Ã¢â‚¬â€ e.g. "Prepare" Ã¢â‚¬â€
        // don't open the order modal, let the button's own handler run.
        if (e.target.closest('button')) return;
        openOrderModal(this.dataset, this);
      });
    });

    function openOrderModal(data, rowEl) {
      currentOrderRow = rowEl;

      document.getElementById('modalOrderId').textContent = data.id;
      document.getElementById('modalCustomer').textContent = data.customer;
      document.getElementById('modalProduct').textContent = data.product;
      document.getElementById('modalQty').textContent = data.qty;
      document.getElementById('modalAmount').textContent = 'Ã¢â€šÂ±' + data.amount;
      document.getElementById('modalDue').textContent = data.due;
      setStatusBadge(document.getElementById('modalStatus'), data.status);

      const priorityEl = document.getElementById('modalPriority');
      priorityEl.textContent = data.priority;
      priorityEl.className = 'badge ' + data.priorityClass;

      const cancelBtn = document.getElementById('cancelOrderBtn');
      const alreadyCancelled = data.status === 'CANCELLED';
      cancelBtn.classList.toggle('disabled', alreadyCancelled);
      document.getElementById('confirmBox').classList.remove('show');

      document.getElementById('pageContent').classList.add('blurred');
      document.getElementById('orderOverlay').classList.add('active');
    }

    function closeOrderModal() {
      document.getElementById('pageContent').classList.remove('blurred');
      document.getElementById('orderOverlay').classList.remove('active');
      document.getElementById('confirmBox').classList.remove('show');
    }

    const prepareUrlTemplate = @json(route('order-fulfillment.orders.prepare', ['id' => '__ID__']));

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

    if (!csrfMeta) {
      console.error('CSRF meta tag not found. Add <meta name="csrf-token" content="{{ csrf_token() }}"> inside <head>. The Prepare button will not work without it.');
    }

    function prepareOrder(orderId, btn) {
      console.log('prepareOrder called for order', orderId);

      if (btn.disabled) return;

      if (!csrfToken) {
        alert('Missing CSRF token on this page Ã¢â‚¬â€ check the browser console for details.');
        return;
      }

      btn.disabled = true;
      const originalText = btn.textContent;
      btn.textContent = 'Moving...';

      const url = prepareUrlTemplate.replace('__ID__', encodeURIComponent(orderId));
      console.log('prepareOrder POSTing to', url);

      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
      })
        .then(function (res) {
          console.log('prepareOrder response status:', res.status);
          if (!res.ok) {
            return res.json().catch(function () { return {}; }).then(function (body) {
              throw new Error(body.message || ('Request failed with status ' + res.status));
            });
          }
          return res.json();
        })
        .then(function (data) {
          if (!data.success) throw new Error(data.message || 'Update failed');

          const row = btn.closest('.order-row');
          row.dataset.status = 'PACKING';

          setStatusBadge(row.querySelector('.badge.status'), 'PACKING');

          // Order has moved past NEW Ã¢â‚¬â€ no action button needed anymore.
          btn.remove();

          // If the floating window is currently open for this same order,
          // keep it in sync with the new status.
          if (currentOrderRow === row) {
            setStatusBadge(document.getElementById('modalStatus'), 'PACKING');
          }
        })
        .catch(function (err) {
          console.error('prepareOrder failed:', err);
          alert('Could not move this order to packing: ' + err.message);
          btn.disabled = false;
          btn.textContent = originalText;
        });
    }
    /* =================== end Prepare -> Packing =================== */
    document.getElementById('cancelOrderBtn').addEventListener('click', function () {
      if (this.classList.contains('disabled')) return;
      document.getElementById('confirmBox').classList.add('show');
    });

    document.getElementById('noKeepBtn').addEventListener('click', function () {
      document.getElementById('confirmBox').classList.remove('show');
    });

    const cancelUrlTemplate = @json(route('order-fulfillment.orders.cancel', ['id' => '__ID__']));

    document.getElementById('yesCancelBtn').addEventListener('click', function () {
      if (!currentOrderRow) return;

      const yesBtn  = this;
      const orderId = currentOrderRow.dataset.id;

      if (!csrfToken) {
        alert('Missing CSRF token on this page Ã¢â‚¬â€ check the browser console for details.');
        return;
      }

      yesBtn.disabled = true;
      const originalText = yesBtn.textContent;
      yesBtn.textContent = 'Cancelling...';

      const url = cancelUrlTemplate.replace('__ID__', encodeURIComponent(orderId));

      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
      })
        .then(function (res) {
          if (!res.ok) {
            return res.json().catch(function () { return {}; }).then(function (body) {
              throw new Error(body.message || ('Request failed with status ' + res.status));
            });
          }
          return res.json();
        })
        .then(function (data) {
          if (!data.success) throw new Error(data.message || 'Cancel failed');

          // ---- Update modal ----
          setStatusBadge(document.getElementById('modalStatus'), 'CANCELLED');
          const priorityEl = document.getElementById('modalPriority');
          priorityEl.textContent = 'Ã¢â‚¬â€';
          priorityEl.className = 'badge';
          document.getElementById('cancelOrderBtn').classList.add('disabled');
          document.getElementById('confirmBox').classList.remove('show');

          // ---- Update the row ----
          currentOrderRow.dataset.status = 'CANCELLED';
          currentOrderRow.dataset.priority = 'CANCELLED';

          setStatusBadge(currentOrderRow.querySelector('.badge.status'), 'CANCELLED');

          // Priority badge disappears entirely for cancelled orders.
          const rowPriorityBadge = currentOrderRow.querySelector('td .badge:not(.status)');
          if (rowPriorityBadge) rowPriorityBadge.remove();

          // Prepare button (if the order was still NEW) disappears too.
          const prepareBtn = currentOrderRow.querySelector('.btn-prepare');
          if (prepareBtn) prepareBtn.remove();

          yesBtn.disabled = false;
          yesBtn.textContent = originalText;

          setTimeout(closeOrderModal, 500);
        })
        .catch(function (err) {
          console.error('cancelOrder failed:', err);
          alert('Could not cancel this order: ' + err.message);
          yesBtn.disabled = false;
          yesBtn.textContent = originalText;
        });
    });

    /* ===================== Search + Filter (working) ===================== */
    const searchInput   = document.getElementById('orderSearch');
    const filterBtn      = document.getElementById('filterBtn');
    const filterPanel    = document.getElementById('filterPanel');
    const filterOverlay  = document.getElementById('filterOverlay');
    const filterBadge    = document.getElementById('filterBadge');
    const noResultsRow   = document.getElementById('noResultsRow');
    const priorityChecks = document.querySelectorAll('.priority-check');

    function activePriority() {
      const checked = Array.from(priorityChecks).find(c => c.checked);
      return checked ? checked.value : '';
    }

    function applyOrderFilters() {
      const query = searchInput.value.trim().toLowerCase();
      const active = activePriority();
      let visibleCount = 0;

      orderRows.forEach(function (row) {
        const d = row.dataset;
        const haystack = [d.id, d.customer, d.product, d.status, d.due]
          .join(' ')
          .toLowerCase();

        const matchesSearch = query === '' || haystack.includes(query);
        const matchesPriority = active === '' || d.priority === active;
        const visible = matchesSearch && matchesPriority;

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
    priorityChecks.forEach(c => c.addEventListener('change', applyOrderFilters));
    searchInput.addEventListener('input', applyOrderFilters);
    /* =================== end Search + Filter =================== */
  </script>

</body>
</html>
