@extends('procurement::layouts.dashboard')

@php($pageKey = 'purchase-orders')

@section('title', 'Purchase Orders')

@section('content')
<section id="page-purchase-orders" data-next-po="{{ $nextPoSeq }}">
  <div class="page-head">
    <h1>Purchase Orders</h1>
    <p>All purchase orders for Techforge PC Solutions</p>
  </div>

  <div class="panel">
    <div class="table-toolbar">
      <h2>All Purchase Orders</h2>
      <div class="search-box">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/><path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <input placeholder="Search..." oninput="filterTable('po-table', this.value)">
      </div>

      <button class="toolbar-btn">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M3 5h18l-7 8v6l-4 2v-8L3 5z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
        Filter
      </button>
      <button class="toolbar-btn primary" onclick="openAddModal('po')">+ New PO</button>
    </div>

    <div class="filter-tabs" id="po-filter-tabs">
      <div class="tab active" data-filter="all">All <span class="tab-count">{{ $counts['all'] }}</span></div>
      <div class="tab" data-filter="pending">Pending <span class="tab-count">{{ $counts['pending'] }}</span></div>
      <div class="tab" data-filter="processing">Processing <span class="tab-count">{{ $counts['processing'] }}</span></div>
      <div class="tab" data-filter="cancel">Cancel <span class="tab-count">{{ $counts['cancel'] }}</span></div>
      <div class="tab" data-filter="completed">Completed <span class="tab-count">{{ $counts['completed'] }}</span></div>
    </div>

    <table class="data-table sortable-table" id="po-table">
      <thead>
        <tr>
          <th class="sortable" data-key="po">PO NUMBER<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th class="sortable" data-key="supplier">SUPPLIER<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th class="sortable" data-key="category">CATEGORY<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th>QTY</th>
          <th class="sortable" data-key="amount">AMOUNT<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th class="sortable" data-key="delstatus">DELIVERY<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th class="sortable" data-key="status">STATUS<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th class="sortable sort-desc" data-key="date">DATE<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($purchaseOrders as $po)
          <tr data-status="{{ $po->status }}" data-date="{{ $po->order_date ? \Illuminate\Support\Carbon::parse($po->order_date)->format('Y-m-d') : '' }}" data-amount="{{ $po->amount }}" data-uom="{{ $po->uom }}" data-requested-by="{{ $po->created_by }}" data-requisition-id="{{ $po->requisition_id }}" data-requisition-reference="{{ $po->requisition_reference }}" data-expected="{{ $po->expected_delivery_date ? \Illuminate\Support\Carbon::parse($po->expected_delivery_date)->format('Y-m-d') : '' }}" data-remarks="{{ $po->remarks }}" data-delete-url="{{ route('procurement.purchase-orders.destroy', $po) }}" data-update-url="{{ route('procurement.purchase-orders.update', $po) }}">
            <td><a class="po-link">{{ $po->po_number }}</a></td>
            <td><span class="supplier-pill"><span class="supplier-badge" style="background:{{ $po->supplier->badge_color }}">{{ $po->supplier->initials }}</span>{{ $po->supplier->name }}</span></td>
            <td>{{ $po->category }}</td>
            <td>{{ $po->qty }}</td>
            <td><b>â‚±{{ number_format($po->amount, 0) }}</b></td>
            <td><span class="del-status {{ strtolower($po->delivery_status) }}"><span class="dstat-dot"></span>{{ ucfirst($po->delivery_status) }}</span></td>
            <td><span class="status-pill {{ $po->status }}">{{ ucfirst($po->status) }}</span></td>
            <td>{{ $po->order_date ? \Illuminate\Support\Carbon::parse($po->order_date)->format('M j, Y') : '' }}</td>
            <td>
              <span class="row-actions">
                <button title="View"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg></button>
                <button title="Edit"><svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M4 20h4l10-10-4-4L4 16v4z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg></button>
                <button class="del" title="Delete"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
              </span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="table-footer">
      <div>Showing <b>1-{{ $counts['all'] }}</b> of <b>{{ $counts['all'] }}</b> purchase orders</div>
      <div class="pager"><button class="page-btn">â€¹</button><button class="page-btn active">1</button><button class="page-btn">â€º</button></div>
    </div>
  </div>
</section>
@endsection

