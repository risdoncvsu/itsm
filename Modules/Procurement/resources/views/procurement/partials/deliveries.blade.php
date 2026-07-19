@extends('procurement::layouts.dashboard')

@php
    $pageKey = 'deliveries';
@endphp

@section('title', 'Deliveries')

@section('content')
<section id="page-deliveries" data-next-dr="{{ $nextShipmentSeq }}">
  <div class="page-head">
    <h1>Deliveries</h1>
    <p>Track incoming shipments from suppliers in real time.</p>
  </div>

  <div class="stat-grid">
    <div class="stat-card">
      <div class="stat-label">PENDING</div>
      <div class="stat-value" id="del-stat-transit">{{ $counts['pending'] }}</div>
      <div class="stat-sub info">â— Awaiting receipt</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">IN-TRANSIT</div>
      <div class="stat-value">{{ $counts['intransit'] ?? 0 }}</div>
      <div class="stat-sub up">â— Requires follow-up</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">COMPLETE</div>
      <div class="stat-value" id="del-stat-delayed">{{ $counts['complete'] }}</div>
      <div class="stat-sub" style="color:var(--green);">â— Closed</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">ON-TIME RATE</div>
      <div class="stat-value">94%</div>
      <div class="stat-sub up">â†‘ 2% this month</div>
    </div>
  </div>

<div class="grid-3">
    <div class="panel" style="grid-column: span 3;">
      <div class="table-toolbar">
        <h2>Delivery Log</h2>
        <div class="search-box">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/><path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          <input placeholder="Search" oninput="filterTable('deliveries-table', this.value)">
        </div>
        <button class="toolbar-btn primary" onclick="openAddModal('delivery')">+ Log Delivery</button>
      </div>

      <div class="filter-tabs" id="del-filter-tabs">
        <div class="tab active" data-filter="all">All <span class="tab-count">{{ $counts['all'] }}</span></div>
        <div class="tab" data-filter="pending">Pending <span class="tab-count">{{ $counts['pending'] }}</span></div>
        <div class="tab" data-filter="intransit">In-Transit <span class="tab-count">{{ $counts['intransit'] ?? 0 }}</span></div>
        <div class="tab" data-filter="complete">Complete <span class="tab-count">{{ $counts['complete'] ?? 0 }}</span></div>
      </div>

      <table class="data-table sortable-table" id="deliveries-table">
        <thead>
          <tr>
            <th class="sortable" data-key="ship">SHIP<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
            <th class="sortable" data-key="po">PO<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
            <th class="sortable" data-key="supplier">SUPPLIER<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
            <th>TIMER</th>
            <th class="sortable" data-key="status">DELIVERY STATUS<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
            <th class="sortable sort-asc" data-key="DATE">DATE<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($deliveries as $d)
            @php
              $stage = $d->stage;
              $stepHtml = '';
              for ($i = 0; $i < 4; $i++) {
                  $done = $stage > $i;
                  $active = $stage === $i;
                  $late = $d->status === 'delayed' && $active;
                  $stepClass = $done ? 'done' : ($late ? 'late' : ($active ? 'active' : ''));
                  $stepMark = $done ? 'âœ“' : ($late ? '!' : '');
                  $stepHtml .= '<div class="step '.$stepClass.'">'.$stepMark.'</div>';
                  $lineClass = $stage > $i ? 'done' : '';
                  $stepHtml .= '<div class="line '.$lineClass.'"></div>';
              }
              $lastDone = $stage >= 4;
              $stepHtml .= '<div class="step '.($lastDone ? 'done' : '').'">'.($lastDone ? 'âœ“' : '').'</div>';
            @endphp
            <tr
              data-status="{{ $d->status }}"
              data-date="{{ $d->delivery_date->format('Y-m-d') }}"
              data-stage="{{ $stage }}"
              data-started-at="{{ $d->started_at?->format('Y-m-d H:i:s') ?? '' }}"
              data-timer-minutes="{{ $d->timer_minutes ?? '' }}"
              data-received-at="{{ $d->received_at ?? '' }}"
              data-condition="{{ $d->condition ?? '' }}"
              data-note="{{ e($d->remarks ?? '') }}"
              data-delete-url="{{ route('procurement.deliveries.destroy', $d) }}"
              data-update-url="{{ route('procurement.deliveries.update', $d) }}">
              <td><a class="po-link">{{ $d->shipment_number }}</a></td>
              <td><a class="po-link">{{ $d->purchaseOrder?->po_number }}</a></td>
              <td><span class="supplier-pill"><span class="supplier-badge" style="background:{{ optional($d->supplier)->badge_color ?? '#64748b' }}">{{ optional($d->supplier)->initials ?? 'NA' }}</span>{{ optional($d->supplier)->name ?? 'Unknown Supplier' }}</span></td>
              <td>
                <span class="delivery-timer">--:--</span>
              </td>
              <td><span class="status-pill {{ $d->status }}">{{ ucfirst($d->status) }}</span></td>
              <td>
                @if ($d->status === 'delayed')
                  <span class="delivery-DATE late">{{ (int) floor(now()->diffInDays($d->delivery_date)) }} days late</span>
                @else
                  <span class="delivery-DATE">{{ $d->delivery_date->format('M j, Y') }}</span>
                @endif
              </td>
              <td>
                <span class="row-actions">
                  <button data-action="track" title="Track" onclick="openTrackModal(this)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 2C7 2 4 6 4 10c0 5.5 8 12 8 12s8-6.5 8-12c0-4-3-8-8-8z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="2"/></svg></button>
                  <button data-action="edit" title="Edit"><svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M4 20h4l10-10-4-4L4 16v4z" stroke="currentColor" stroke-width="2"/></svg></button>
                </span>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="table-footer">
        <div>Showing <b>1-{{ $counts['all'] }}</b> of <b>{{ $counts['all'] }}</b> shipments</div>
        <div class="pager"><button class="page-btn">â€¹</button><button class="page-btn active">1</button><button class="page-btn">â€º</button></div>
      </div>
    </div>
  </div>
</section>
@endsection

