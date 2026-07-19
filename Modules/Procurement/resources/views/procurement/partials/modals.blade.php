{{-- Shipment tracking modal --}}
<div class="modal-overlay" id="track-modal" onclick="if(event.target===this) closeTrackModal()">
  <div class="modal-box" style="width:520px;">
    <div class="modal-head">
      <h3 id="track-title">Shipment tracking</h3>
      <button class="modal-close" onclick="closeTrackModal()">âœ•</button>
    </div>
    <div id="track-body"></div>
    <div class="modal-actions">
      <button class="btn btn-view" style="flex:1" onclick="closeTrackModal()">Close</button>
      <button id="track-mark-complete-btn" class="btn btn-approve" style="flex:1" onclick="markDelivered()">Mark Complete</button>
    </div>
  </div>
</div>

{{-- View modal --}}
<div class="modal-overlay" id="view-modal" onclick="if(event.target===this) closeViewModal()">
  <div class="modal-box">
    <div class="modal-head">
      <h3 id="modal-title">Request details</h3>
      <button class="modal-close" onclick="closeViewModal()">âœ•</button>
    </div>
    <div id="modal-body"></div>
    <div class="modal-actions">
      <button type="button" class="btn btn-view" style="flex:1" id="modal-close-btn" onclick="closeViewModal()">Close</button>
    </div>
  </div>
</div>

{{-- Confirm modal --}}
<div class="modal-overlay" id="confirm-modal" onclick="if(event.target===this) closeConfirmModal()">
  <div class="modal-box confirm-modal">
    <div class="modal-head">
      <div>
        <h3>Confirm action</h3>
        <p style="font-size:12px;color:var(--muted);margin-top:4px;">Please confirm before proceeding.</p>
      </div>
      <button class="modal-close" onclick="closeConfirmModal()">âœ•</button>
    </div>
    <div id="confirm-modal-body" style="padding:1rem 1rem 0; color: var(--text);"></div>
    <div class="modal-actions">
      <button type="button" class="btn btn-cancel" onclick="closeConfirmModal()">Cancel</button>
      <button type="button" class="btn btn-reject" onclick="confirmModalAccept()">Confirm</button>
    </div>
  </div>
</div>

{{-- Edit modal --}}
<div class="modal-overlay" id="edit-modal" onclick="if(event.target===this) closeEditModal()">
  <div class="modal-box form-modal-lg">
    <div class="modal-head">
      <div>
        <h3 id="edit-modal-title">Edit record</h3>
        <p style="font-size:12px;color:var(--muted);margin-top:4px;">Update editable fields and save changes.</p>
      </div>
      <button class="modal-close" onclick="closeEditModal()">âœ•</button>
    </div>
    <form id="edit-record-form" onsubmit="saveEditRecord(event)">
      <div class="form-grid" id="edit-modal-body"></div>
      <div class="modal-actions">
        <button type="button" class="btn btn-cancel" onclick="closeEditModal()">Cancel</button>
        <button type="submit" class="btn btn-submit">Save Changes</button>
      </div>
    </form>
  </div>
</div>

{{-- Delete modal --}}
<div class="modal-overlay" id="delete-modal" onclick="if(event.target===this) closeDeleteModal()">
  <div class="modal-box confirm-modal">
    <div class="modal-head">
      <div>
        <h3 id="delete-modal-title">Delete record</h3>
        <p style="font-size:12px;color:var(--muted);margin-top:4px;">Authentication required before deletion.</p>
      </div>
      <button class="modal-close" onclick="closeDeleteModal()">âœ•</button>
    </div>
    <div id="delete-modal-body">
      <div class="confirm-box">
        <div id="delete-modal-target" style="font-weight:700; margin-bottom:6px;"></div>
        <div class="modal-helper">Type <b>DELETE</b> to continue.</div>
        <input id="delete-confirm-input" class="inline-input" placeholder="Type DELETE" oninput="handleDeletePhrase(this.value)">
      </div>
      <div class="confirm-box" id="delete-final-confirm" style="display:none;">
        <div style="font-weight:700; margin-bottom:6px;">Final confirmation</div>
        <div class="modal-helper">This prototype will remove the selected row after confirmation.</div>
      </div>
    </div>
    <div class="modal-actions" id="delete-modal-actions">
      <button type="button" class="btn btn-cancel" id="delete-cancel-btn" onclick="closeDeleteModal()">Cancel</button>
      <button type="button" class="btn btn-reject" id="delete-continue-btn" onclick="continueDeleteFlow()" style="display:none;">Continue</button>
      <button type="button" class="btn btn-reject" id="delete-confirm-btn" onclick="confirmDeleteRecord()" style="display:none;">Confirm Delete</button>
    </div>
  </div>
</div>

@php
    $modalSuppliers = \Modules\Procurement\Models\Supplier::orderBy('name')->get();
    $modalRequisitions = \Modules\Procurement\Models\Requisition::where('status', 'pending')
        ->whereDoesntHave('purchaseOrders')
        ->orderByDesc('date_requested')
        ->get();
    $modalPendingPOs = \Modules\Procurement\Models\PurchaseOrder::with('supplier')
        ->where('delivery_status', '!=', 'complete')
        ->orderByDesc('order_date')
        ->get();
    $modalPoNumbers = $modalPendingPOs->pluck('po_number');
@endphp

{{-- ============ ADD MODALS (PO / Supplier / Req / Delivery) ============ --}}

{{-- Add Purchase Order --}}
<div class="modal-overlay" id="add-po-modal" onclick="if(event.target===this) closeAddModal('po')">
  <div class="modal-box form-modal-lg">
    <div class="modal-head">
      <div>
        <h3>Create New Purchase Order</h3>
        <p style="font-size:12px;color:var(--muted);margin-top:3px;">Create a purchase order from an approved requisition and link it to a supplier.</p>
      </div>
      <button class="modal-close" onclick="closeAddModal('po')">âœ•</button>
    </div>

    <form id="add-po-form" action="{{ route('procurement.purchase-orders.store') }}" onsubmit="submitAddPO(event)">
      <div class="form-grid">
        <div class="form-field">
          <label>PO Number <span class="req">*</span></label>
          <input name="po" required readonly>
          <input type="hidden" name="createdBy" value="">
          <span class="hint">Auto-generated by the system.</span>
        </div>
        <div class="form-field">
          <label>Req. Number</label>
          <select name="req">
            <option value="">None</option>
            @foreach ($modalRequisitions as $req)
              @php
                $reqNotes = preg_split('/\s*\|\s*/', trim((string) $req->notes));
                $reqSupplier = '';
                foreach ($reqNotes as $segment) {
                  if (str_starts_with($segment, 'Supplier:')) {
                    $reqSupplier = trim(substr($segment, strlen('Supplier:')));
                  }
                }
              @endphp
              <option value="{{ $req->req_number }}" data-supplier="{{ $reqSupplier }}" data-item="{{ $req->item }}" data-supplier-item="{{ $req->supplier_item }}" data-qty="{{ $req->qty }}" data-amount="{{ $req->amount }}" data-uom="{{ $req->uom }}" data-category="{{ $req->category ?? '' }}">{{ $req->req_number }} â€” {{ $req->item }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-field">
          <label>Supplier <span class="req">*</span></label>
          <select name="supplier" required>
            <option value="">Select supplier...</option>
            @foreach ($modalSuppliers as $supplier)
              <option value="{{ $supplier->name }}" data-products="{{ $supplier->product_items }}" data-category="{{ $supplier->category }}">{{ $supplier->name }}</option>
            @endforeach
          </select>
        </div>
        
        <div class="form-field">
          <label>Category <span class="req">*</span></label>
          <select name="category" required>
            <option value="">Select category...</option>
            <option>Computer Equipment</option>
            <option>Office Supplies</option>
            <option>Components</option>
            <option>Storage</option>
            <option>Power Supplies</option>
            <option>Cables &amp; Misc</option>
            <option>Cases &amp; Cooling</option>
          </select>
        </div>
        <div class="form-field">
          <label>Quantity <span class="req">*</span></label>
          <input type="number" name="qty" min="1" step="1" required>
        </div>
        <input type="hidden" name="orderDate" value="">
        <div class="form-field">
          <label>Expected Delivery <span class="req">*</span></label>
          <input type="date" name="expected" required>
        </div>

        <div class="form-field full">
          <label>Item Description <span class="req">*</span></label>
          <input name="item" placeholder="e.g. USB-C Cables Ã— 200, Adapters Ã— 100" required>
          <span class="hint" id="po-item-hint">Select a supplier or requisition to auto-fill details.</span>
        </div>
        <div class="form-field">
          <label>Unit / UoM <span class="req">*</span></label>
          <select name="uom" required>
            <option value="">Select unit...</option>
            <option value="pcs">pcs</option>
            <option value="box">box</option>
            <option value="set">set</option>
          </select>
        </div>
        <div class="form-field">
          <label>Total Amount (â‚±) <span class="req">*</span></label>
          <input type="number" name="amount" min="0" step="0.01" placeholder="0.00" required>
        </div>
        <div class="form-field full">
          <label>Remarks</label>
          <textarea name="remarks" placeholder="Any additional notes for the request."></textarea>
        </div>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-cancel" onclick="closeAddModal('po')">Cancel</button>
        <button type="submit" class="btn btn-submit">Create Purchase Order</button>
      </div>
    </form>
  </div>
</div>

{{-- Add Supplier --}}
<div class="modal-overlay" id="add-supplier-modal" onclick="if(event.target===this) closeAddModal('supplier')">
  <div class="modal-box form-modal-lg">
    <div class="modal-head">
      <div>
        <h3>Add New Supplier</h3>
        <p style="font-size:12px;color:var(--muted);margin-top:3px;">Register a new supplier record.</p>
      </div>
      <button class="modal-close" onclick="closeAddModal('supplier')">âœ•</button>
    </div>

    <form id="add-supplier-form" action="{{ route('procurement.suppliers.store') }}" onsubmit="submitAddSupplier(event)">
      <div class="form-grid">
        <div class="form-field">
          <label>Supplier Name <span class="req">*</span></label>
          <input name="name" placeholder="e.g. TechSource Inc." required>
        </div>
        <div class="form-field">
          <label>Contact Person <span class="req">*</span></label>
          <input name="contact" placeholder="Full name" required>
        </div>
        <div class="form-field">
          <label>Email <span class="req">*</span></label>
          <input type="email" name="email" placeholder="name@company.com" required>
        </div>
        <div class="form-field">
          <label>Phone Number <span class="req">*</span></label>
          <input name="phone" placeholder="+63 9XX XXX XXXX" required>
        </div>
         <div class="form-field">
          <label>Initial Status</label>
          <select name="status">
            <option value="active" selected>Active</option>
            <option value="inactive">Inactive</option>
            <option value="blacklisted">Blacklisted</option>
          </select>
        </div>
        <div class="form-field">
          <label>Category <span class="req">*</span></label>
          <select name="category" required>
            <option value="">Select...</option>
            <option>Computer Equipment</option>
            <option>Office Supplies</option>
            <option>Components</option>
            <option>Storage</option>
            <option>Power Supplies</option>
            <option>Cables &amp; Misc</option>
            <option>Cases &amp; Cooling</option>
          </select>
        </div>
        <div class="form-field full">
          <label>Address <span class="req">*</span></label>
          <textarea name="address" placeholder="Full business address" required></textarea>
        </div>
        <div class="form-field full">
          <div class="supplier-products-header">
            <label style="margin:0; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.3px; font-size:12px;">Supplier Products</label>
            <button type="button" class="btn btn-view" onclick="addSupplierProductRow()">+ Add product</button>
          </div>
          <div class="supplier-products-table">
            <div class="supplier-product-grid supplier-product-grid--header">
              <div></div>
              <div>Product</div>
              <div>SKU type</div>
              <div>SKU code</div>
              <div>Supply price</div>
              <div></div>
            </div>
            <div id="supplier-products-list"></div>
          </div>
          <input type="hidden" name="products" value="[]">
          <span class="hint">Check products to save them with the supplier.</span>
        </div>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-cancel" onclick="closeAddModal('supplier')">Cancel</button>
        <button type="submit" class="btn btn-submit">Save Supplier</button>
      </div>
    </form>
  </div>
</div>

{{-- Add Requisition --}}
<div class="modal-overlay" id="add-req-modal" onclick="if(event.target===this) closeAddModal('req')">
  <div class="modal-box form-modal">
    <div class="modal-head">
      <div>
        <h3>New Requisition Request</h3>
        <p style="font-size:12px;color:var(--muted);margin-top:3px;">Create a requisition request with its item, quantity, amount, and unit.</p>
      </div>
      <button class="modal-close" onclick="closeAddModal('req')">âœ•</button>
    </div>

    <form id="add-req-form" action="{{ route('procurement.requisitions.store') }}" onsubmit="submitAddReq(event)">
      <div class="form-grid">
        <div class="form-field">
          <label>Requisition No. <span class="req">*</span></label>
          <input name="rq" required readonly>
        </div>
        <div class="form-field">
          <label>Requested By <span class="req">*</span></label>
          <input name="requester" placeholder="Full name" required>
        </div>
        <div class="form-field">
          <label>Department <span class="req">*</span></label>
          <select name="dept" required>
            <option value="">Select department...</option>
            <option>IT</option>
            <option>HR</option>
            <option>Finance</option>
            <option>Operations</option>
            <option>Marketing</option>
            <option>Sales</option>
            <option>Administration</option>
          </select>
        </div>
        <div class="form-field">
          <label>Date Requested <span class="req">*</span></label>
          <input type="date" name="dateReq" required>
        </div>
        <div class="form-field">
          <label>Supplier <span class="req">*</span></label>
          <select name="supplier" required>
            <option value="">Select supplier...</option>
            @foreach ($modalSuppliers as $supplier)
              <option value="{{ $supplier->name }}" data-products="{{ $supplier->product_items }}">{{ $supplier->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-field">
          <label>Item / Product <span class="req">*</span></label>
          <select name="item" required disabled>
            <option value="">Select supplier first</option>
          </select>
          <input type="hidden" name="supplier_item" value="">
        </div>
        <div class="form-field">
          <label>Quantity <span class="req">*</span></label>
          <input type="number" name="qty" min="1" step="1" required>
        </div>
        <div class="form-field">
          <label>Unit / UoM <span class="req">*</span></label>
          <select name="uom" required>
            <option value="">Select unit...</option>
            <option value="pcs">pcs</option>
            <option value="box">box</option>
            <option value="set">set</option>
          </select>
        </div>
        <div class="form-field full">
          <label>Justification / Notes</label>
          <textarea name="notes" placeholder="Why is this needed?"></textarea>
        </div>
        <input type="hidden" name="status" value="pending">
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-cancel" onclick="closeAddModal('req')">Cancel</button>
        <button type="submit" class="btn btn-submit">Submit Requisition</button>
      </div>
    </form>
  </div>
</div>

{{-- Add Delivery --}}
<div class="modal-overlay" id="add-delivery-modal" onclick="if(event.target===this) closeAddModal('delivery')">
  <div class="modal-box form-modal">
    <div class="modal-head">
      <div>
        <h3>Log New Delivery</h3>
        <p style="font-size:12px;color:var(--muted);margin-top:3px;">Record the shipment linked to a purchase order and track its progress over time.</p>
      </div>
      <button class="modal-close" onclick="closeAddModal('delivery')">âœ•</button>
    </div>

    <form id="add-delivery-form" action="{{ route('procurement.deliveries.store') }}" onsubmit="submitAddDelivery(event)">
      <div class="form-grid">
        <div class="form-field">
          <label>Shipment No. <span class="req">*</span></label>
          <input name="dr" required readonly>
          <span class="hint">Auto-generated by the system.</span>
        </div>
        <div class="form-field">
          <label>PO Number <span class="req">*</span></label>
          <select name="po" required>
            <option value="">Select PO...</option>
            @foreach ($modalPendingPOs as $po)
              <option value="{{ $po->po_number }}" data-supplier="{{ $po->supplier?->name ?? '' }}" data-item="{{ $po->item }}" data-qty="{{ $po->qty }}" data-amount="{{ $po->amount }}" data-uom="{{ $po->uom }}" data-expected="{{ $po->expected_delivery_date?->format('Y-m-d') }}">{{ $po->po_number }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-field">
          <label>Supplier <span class="req">*</span></label>
          <input name="supplier" placeholder="Auto-fill / edit" required>
        </div>
        <div class="form-field">
          <label>Delivery Date <span class="req">*</span></label>
          <input type="date" name="delDate" required>
        </div>
        <div class="form-field">
          <label>Item</label>
          <input name="items" readonly placeholder="Auto-filled from selected PO">
        </div>
      
        <div class="form-field">
          <label>Unit / UoM <span class="req">*</span></label>
          <select name="uom" required>
            <option value="">Select unit...</option>
            <option value="pcs">pcs</option>
            <option value="box">box</option>
            <option value="set">set</option>
          </select>
        </div>
        <div class="form-field">
          <label>Expected Delivery <span class="req">*</span></label>
          <input type="date" name="expected" required>
        </div>
        <div class="form-field">
          <label>Timer (minutes) <span class="req">*</span></label>
          <input type="number" name="timer_minutes" min="1" step="1" value="60" required>
        </div>
          <div class="form-field">
          <label>Quantity</label>
          <input type="number" name="qty" min="1" readonly>
        </div>
        <div class="form-field">
          <label>Total Amount</label>
          <input type="number" name="amount" min="0" step="0.01" readonly>
        </div>
        
        <div class="form-field full">
          <label>Remarks</label>
          <textarea name="remarks" placeholder="Delivery notes, issues, or exceptions"></textarea>
        </div>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-cancel" onclick="closeAddModal('delivery')">Cancel</button>
        <button type="submit" class="btn btn-submit">Log Delivery</button>
      </div>
    </form>
  </div>
</div>


