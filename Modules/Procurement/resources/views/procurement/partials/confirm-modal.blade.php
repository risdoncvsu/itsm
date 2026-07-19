<div class="modal-overlay" id="confirm-modal" onclick="if(event.target===this) closeConfirmModal()">
  <div class="modal-box confirm-modal">
    <div class="modal-head">
      <div>
        <h3>Confirm action</h3>
        <p style="font-size:12px;color:var(--muted);margin-top:4px;">Please confirm before proceeding.</p>
      </div>
      <button class="modal-close" onclick="closeConfirmModal()">âœ•</button>
    </div>
    <div id="confirm-modal-body" style="padding: 1rem 1rem 0; color: var(--text);"></div>
    <div class="modal-actions">
      <button type="button" class="btn btn-cancel" onclick="closeConfirmModal()">Cancel</button>
      <button type="button" class="btn btn-reject" onclick="confirmModalAccept()">Confirm</button>
    </div>
  </div>
</div>

