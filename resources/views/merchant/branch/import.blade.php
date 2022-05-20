<div class="modal fade" id="importBranchModal" tabindex="-1" role="dialog" aria-labelledby="importBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importBranchModalLabel">{{ __('labels.import') }} {{ __('labels.branches') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('merchant.branches.import') }}" method="post" enctype="multipart/form-data" role="form" class="no-load no-disabled">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file" class="col-form-label">{{ __('labels.upload') }} {{ trans_choice('labels.file', 2) }} <span class="text-danger">*</span></label>
                        <input type="file" id="file" name="file" class="form-control-file custom-img-input @error('file') is-invalid @enderror" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        @error('file')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <ul class="pl-3 mt-3">
                            {!! trans_choice('messages.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'XLSX']) !!}
                            <li>Download format <a href="{{ asset('assets/import_branches.xlsx') }}" download="import_branches.xlsx">here</a></li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-purple">
                        <i class="fas fa-paper-plane"></i>
                        {{ __('labels.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>