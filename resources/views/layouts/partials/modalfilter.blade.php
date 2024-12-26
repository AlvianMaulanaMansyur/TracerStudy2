<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="GET" action="{{ route('admin.alumni.filter') }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Alumni</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="angkatan">Angkatan</label>
                        <input type="number" name="angkatan" class="form-control" id="angkatan">
                    </div>
                    <div class="form-group">
                        <label for="tahun_lulus">Tahun Lulus</label>
                        <input type="number" name="tahun_lulus" class="form-control" id="tahun_lulus">
                    </div>
                    <div class="form-group">
                        <label for="gelombang_wisuda">Gelombang Wisuda</label>
                        <input type="number" name="gelombang_wisuda" class="form-control" id="gelombang_wisuda">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>
