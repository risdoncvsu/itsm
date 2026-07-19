@extends('procurement::layouts.dashboard')

@php
    $pageKey = 'dashboard';
@endphp

@section('title', 'Dashboard')

@section('content')
<section id="page-dashboard">
  <div class="page-head">
    <h1>Procurement</h1>
    <p>Manage purchase orders, suppliers, and requisitions.</p>
  </div>

  <div class="stat-grid">
    <div class="stat-card">
      <div class="stat-label">ACTIVE POST</div>
      <div class="stat-value" id="dash-stat-po">{{ $stats['activePos'] }}</div>
      <div class="stat-sub up">â†‘ {{ $stats['totalPos'] }} total</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">SUPPLIERS</div>
      <div class="stat-value" id="dash-stat-sup">{{ $stats['suppliers'] }}</div>
      <div class="stat-sub up">â†‘ {{ $stats['activeSuppliers'] }} active</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">REQUISITIONS</div>
      <div class="stat-value" id="dash-stat-req">{{ $stats['requisitions'] }}</div>
      <div class="stat-sub up">âœ“ {{ $stats['approvedRequisitions'] }} approved</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">DELIVERIES</div>
      <div class="stat-value" id="dash-stat-del">{{ $stats['deliveries'] }}</div>
      <div class="stat-sub warn">â— Track shipment progress</div>
    </div>
  </div>

  <div class="dash-grid-3">
    <div class="panel">
      <h2>Spend by Category</h2>
      <div class="panel-sub">All purchase orders, in â‚±</div>
      @php
          $catSpend = \Modules\Procurement\Models\PurchaseOrder::selectRaw('category, sum(amount) as total')->groupBy('category')->orderByDesc('total')->get();
          $maxSpend = $catSpend->max('total') ?: 1;
      @endphp
      <div class="bars-row dash" id="dash-category-bars" style="margin-top:18px; gap:16px; align-items:flex-end; min-height:240px;">
        @foreach ($catSpend as $c)
          @php $height = round((($c->total) / $maxSpend) * 200); @endphp
          <div class="bar-col">
            <div class="bar-val">â‚±{{ number_format($c->total / 1000, 1) }}k</div>
            <div class="bar" data-h="{{ $height }}" style="height:0;background:var(--blue)"></div>
            <div class="bar-label" title="{{ $c->category }}">{{ \Illuminate\Support\Str::limit($c->category, 12) }}</div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="panel">
      <h2>PO Status Split</h2>
      <div class="panel-sub">{{ $stats['totalPos'] }} total purchase orders</div>
      <br><br>

      @php
          $statusCounts = [
              'processing' => ($poStatusCounts['approved'] ?? 0) + ($poStatusCounts['processing'] ?? 0),
              'pending' => $poStatusCounts['pending'] ?? 0,
              'cancel' => ($poStatusCounts['rejected'] ?? 0) + ($poStatusCounts['cancel'] ?? 0),
              'completed' => $poStatusCounts['completed'] ?? 0,
          ];
          $order = [
             'completed' => '#188a5b',
              'pending' => 'var(--orange)',
              'processing' => 'var(--blue)',
              'cancel' => '#6b7280',
              
          ];
          $total = max(array_sum($statusCounts), 1);
          $circumference = 427.3;
          $offset = 0;
          $segments = [];
          foreach ($order as $status => $color) {
              $count = $statusCounts[$status] ?? 0;
              $pct = round(($count / $total) * 100);
              $len = round(($count / $total) * $circumference, 1);
              $segments[] = ['status' => $status, 'color' => $color, 'pct' => $pct, 'len' => $len, 'offset' => $offset];
              $offset -= $len;
          }
      @endphp
      <div class="donut-wrap big">
        <div class="donut-svg-wrap">
          <svg id="po-status-donut" width="170" height="170" viewBox="0 0 170 170">
            <g transform="rotate(-90 85 85)">
              <circle cx="85" cy="85" r="68" fill="none" stroke="#eef1f7" stroke-width="18"></circle>
              @foreach ($segments as $seg)
                <circle class="donut-seg" data-type="{{ $seg['status'] }}" data-label="{{ ucfirst($seg['status']) }}" data-pct="{{ $seg['pct'] }}"
                  cx="85" cy="85" r="68" fill="none" stroke="{{ $seg['color'] }}" stroke-width="18"
                  stroke-dasharray="0 {{ $circumference }}" stroke-dashoffset="0"
                  data-dasharray="{{ $seg['len'] }} {{ $circumference }}" data-dashoffset="{{ $seg['offset'] }}" stroke-linecap="butt"></circle>
              @endforeach
            </g>
          </svg>
          <div class="donut-hole" id="po-status-center">
            <b>{{ $stats['totalPos'] }}</b>
            <span>total POs</span>
          </div>
        </div>
        <div class="donut-legend-grid">
          @foreach ($segments as $seg)
            <div class="legend-row"><span class="dot" style="background:{{ $seg['color'] }}"></span>{{ ucfirst($seg['status']) }}<b>{{ $seg['pct'] }}%</b></div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="panel">
      <h2>Top Suppliers</h2>
      <div class="panel-sub">By total PO spend</div>
      @php
        $topMax = $topSuppliers->max('purchase_orders_sum_amount') ?: 1;
      @endphp
      @foreach ($topSuppliers as $s)
        <div class="top-supplier-row">
          <div class="ts-rank {{ $loop->first ? 'top' : '' }}">{{ $loop->iteration }}</div>
          <div class="ts-body">
            <div class="ts-name" title="{{ $s->name }}">{{ Str::limit($s->name, 20) }}</div>
            <div class="ts-bar-bg"><div class="ts-bar-fill" data-w="{{ round((($s->purchase_orders_sum_amount ?: 0) / $topMax) * 100) }}%"></div></div>
          </div>
          <div class="ts-val">â‚±{{ number_format(($s->purchase_orders_sum_amount ?: 0) / 1000, 1) }}k</div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="dash-po-grid">
    <div class="panel">
      <div class="filter-tabs" id="dash-po-tabs">
        <div class="tab active" data-filter="recent">Recent Purchase Orders</div>
        <div class="tab" data-filter="draft">Draft</div>
        <div class="tab" data-filter="pending">Pending</div>
        <a href="{{ route('procurement.purchase-orders') }}" style="margin-left:auto; color:var(--blue); font-weight:600; font-size:13px;">View all purchase orders â†’</a>
      </div>
      <table class="data-table" id="dash-po-table">
        <thead>
          <tr>
            <th class="sortable" data-key="po">PO#</th>
            <th class="sortable" data-key="supplier">SUPPLIER</th>
            <th class="sortable" data-key="amount">AMOUNT</th>
            <th class="sortable" data-key="delstatus">DELIVERY</th>
            <th class="sortable" data-key="status">STATUS</th>
            <th class="sortable sort-desc" data-key="date">DATE</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($recentPos as $po)
            @php
              $supplier = $po->supplier;
              $supplierName = $supplier?->name ?? 'Unknown supplier';
              $supplierInitials = $supplier?->initials ?? 'NA';
              $supplierBadge = $supplier?->badge_color ?? '#64748b';
            @endphp
            <tr data-status="{{ $po->status }}">
              <td><a href="{{ route('procurement.purchase-orders') }}" class="po-link">{{ $po->po_number }}</a></td>
              <td><span class="supplier-pill"><span class="supplier-badge" style="background:{{ $supplierBadge }}">{{ $supplierInitials }}</span>{{ $supplierName }}</span></td>
              <td><b>â‚±{{ number_format($po->amount, 0) }}</b></td>
              <td><span class="del-status {{ $po->delivery_status }}"><span class="dstat-dot"></span>{{ ucfirst($po->delivery_status) }}</span></td>
              <td><span class="status-pill {{ $po->status }}">{{ ucfirst($po->status) }}</span></td>
              <td>{{ $po->order_date->format('M j, Y') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="panel dash-del-panel">
      <h2><span class="live-pulse"></span>Deliver Recent</h2>
      <div class="panel-sub">Live shipment overview Â· updated moments ago</div>
      <div class="dash-del-list" id="dash-del-list">
        @foreach ($deliveries->take(3) as $d)
          @php
            $deliverySupplier = $d->supplier;
            $deliveryPo = $d->purchaseOrder;
            $deliverySupplierName = $deliverySupplier?->name ?? 'Unknown supplier';
            $deliverySupplierInitials = $deliverySupplier?->initials ?? 'NA';
            $deliverySupplierBadge = $deliverySupplier?->badge_color ?? '#64748b';
            $deliveryPoNumber = $deliveryPo?->po_number ?? 'Unlinked PO';
          @endphp
          <div class="dash-del-item">
            <div class="del-avatar" style="background:{{ $deliverySupplierBadge }}">{{ $deliverySupplierInitials }}</div>
            <div class="del-body">
              <div class="del-line1">{{ $d->shipment_number }} Â· {{ $deliveryPoNumber }}</div>
              <div class="del-line2"><span class="del-status {{ strtolower(str_replace([' ', '-'], '', $d->status)) }}" style="padding:2px 8px;font-size:10.5px;"><span class="dstat-dot"></span>{{ ucfirst($d->status) }}</span> &nbsp;Â·&nbsp; {{ $deliverySupplierName }}</div>
            </div>
            <div class="del-DATE {{ $d->status === 'delayed' ? 'late' : '' }}">{{ $d->delivery_date->format('M j') }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection

