@extends('procurement::layouts.dashboard')

@php($pageKey = 'suppliers')

@section('title', 'Suppliers')

@section('content')
<section id="page-suppliers">
  <div class="page-head">
    <h1>Suppliers</h1>
    <p>{{ $suppliers->count() }} registered suppliers</p>
  </div>

  <div class="panel">
    <div class="table-toolbar">
      <h2>Supplier Directory</h2>
      <div class="search-box">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/><path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <input placeholder="Search suppliers..." oninput="filterTable('suppliers-table', this.value)">
      </div>
      <button class="toolbar-btn primary" onclick="openAddModal('supplier')">+ Add Supplier</button>
    </div>

    <table class="data-table sortable-table" id="suppliers-table">
      <thead>
        <tr>
          <th class="sortable" data-key="name">SUPPLIER<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th class="sortable" data-key="contact">CONTACT PERSON<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th class="sortable" data-key="email">EMAIL<span class="sort-arrows"><svg viewBox="0 0 8 5"><path d="M4 0L8 5H0z" fill="currentColor"/></svg><svg viewBox="0 0 8 5"><path d="M4 5L0 0h8z" fill="currentColor"/></svg></span></th>
          <th>PHONE</th>
          <th>ADDRESS</th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody id="suppliers-tbody">
        @foreach ($suppliers as $s)
          <tr data-delete-url="{{ route('procurement.suppliers.destroy', $s) }}" data-update-url="{{ route('procurement.suppliers.update', $s) }}">
            <td><span class="supplier-pill"><span class="supplier-badge" style="background:{{ $s->badge_color }}">{{ $s->initials }}</span>{{ $s->name }}</span></td>
            <td>{{ $s->contact_person }}</td>
            <td>{{ $s->email }}</td>
            <td>{{ $s->phone }}</td>
            <td>{{ $s->address }}</td>
            <td>
              <span class="row-actions">
                <button title="View"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg></button>
                <button title="Edit"><svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M4 20h4l10-10-4-4L4 16v4z" stroke="currentColor" stroke-width="2"/></svg></button>
                <button class="del" title="Delete"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
              </span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="table-footer">
      <div>Showing <b>1-{{ $suppliers->count() }}</b> of <b>{{ $suppliers->count() }}</b> suppliers</div>
      <div class="pager"><button class="page-btn">â€¹</button><button class="page-btn active">1</button><button class="page-btn">â€º</button></div>
    </div>
  </div>
</section>
@endsection

