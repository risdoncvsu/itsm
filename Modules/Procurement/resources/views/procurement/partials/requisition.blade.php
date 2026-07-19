@extends('procurement::layouts.dashboard')

@php($pageKey = 'requisitions')

@section('title', 'Requisitions')

@section('content')
<section id="page-requisitions" data-next-req="{{ $nextReqSeq }}">
  <div class="page-head">
    <h1>Requisitions</h1>
    <p>All purchase requisitions</p>
  </div>

  <div class="panel">
    <div class="table-toolbar">
      <h2>Requisition List</h2>
      <div class="search-box">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/><path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <input placeholder="Search" oninput="filterTable('requisitions-table', this.value)">
      </div>
      <button class="toolbar-btn">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M3 5h18l-7 8v6l-4 2v-8L3 5z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
        Filter
      </button>
      <button class="toolbar-btn primary" onclick="openAddModal('req')">+ New Requisition</button>
    </div>

    <div class="filter-tabs" id="req-filter-tabs">
      <div class="tab active" data-filter="all">All <span class="tab-count">{{ $counts['all'] }}</span></div>
      <div class="tab" data-filter="pending">Pending <span class="tab-count">{{ $counts['pending'] }}</span></div>
      <div class="tab" data-filter="processing">Approved <span class="tab-count">{{ $counts['approved'] }}</span></div>
      <div class="tab" data-filter="rejected">Rejected <span class="tab-count">{{ $counts['rejected'] }}</span></div>
    </div>

    <table class="data-table sortable-table" id="requisitions-table">
      <thead>
        <tr>
          <th class="sortable" data-key="req">REQ #<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th class="sortable" data-key="item">ITEM<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th class="sortable" data-key="qty">QTY<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          
          <th class="sortable" data-key="dept">DEPARTMENT<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th>REQUESTED BY</th>
          <th class="sortable" data-key="status">STATUS<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th class="sortable sort-desc" data-key="date">DATE<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($requisitions as $r)
          <tr data-status="{{ $r->status }}" data-date="{{ $r->date_requested->format('Y-m-d') }}" data-item="{{ $r->item }}" data-qty="{{ $r->qty }}" data-amount="{{ $r->amount }}" data-uom="{{ $r->uom }}" data-requester="{{ $r->requested_by }}" data-dept="{{ $r->department }}" data-supplier="{{ $r->supplier_name }}" data-supplier-item="{{ $r->supplier_item }}" data-notes="{{ $r->notes }}" data-delete-url="{{ route('procurement.requisitions.destroy', $r) }}" data-update-url="{{ route('procurement.requisitions.update', $r) }}" data-ref="{{ $r->req_number }}">
            <td><a class="po-link">{{ $r->req_number }}</a></td>
            <td>{{ $r->item }}</td>
            <td>{{ $r->qty }}</td>
            <td>{{ $r->department }}</td>
            <td>{{ $r->requested_by }}</td>
            <td><span class="status-pill {{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
            <td>{{ $r->date_requested->format('M j, Y') }}</td>
            <td>
              <span class="row-actions">
                <button title="View"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg></button>
                <button title="Edit"><svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M4 20h4l10-10-4-4L4 16v4z" stroke="currentColor" stroke-width="2"/></svg></button>
                @if($r->status === 'processing')
                  <button data-action="create-po" title="Create PO"><svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
                @endif
                <button class="del" title="Delete"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
              </span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="table-footer">
      <div>Showing <b>1-{{ $counts['all'] }}</b> of <b>{{ $counts['all'] }}</b> requisitions</div>
      <div class="pager"><button class="page-btn">â€¹</button><button class="page-btn active">1</button><button class="page-btn">â€º</button></div>
    </div>
  </div>
</section>
@endsection

