@if ($errors->has('create.*'))

@push('scripts')

<script type="text/javascript">
    $(window).on('load', function () {
        $('#createTranslationModal').modal('show');
    });
</script>

@endpush

@endif

<div class="modal fade show hide" id="createTranslationModal" tabindex="-1" role="dialog" aria-labelledby="createTranslationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTranslationModalLabel">{{ __('modules.create', ['module' => trans_choice('modules.language', 1)]) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('locale.languages.store') }}" method="POST" role="form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="new_version" class="col-form-label">{{ __('labels.new_version') }}</label>
                        <input type="text" id="new_version" name="new_version" value="{{ old('new_version') }}" class="form-control @error('new_version') is-invalid @enderror">
                        @error('new_version')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="file" class="col-form-label">{{ trans_choice('labels.upload_file', 1) }}</label>
                        <input type="file" id="file" name="file" class="form-control-file @error('file') is-invalid @enderror">
                        @error('file')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="use_version">{{ __('labels.use_version') }}</label>
                        <div class="icheck-purple">
                            <input type="checkbox" name="use_version" id="use_version" {{ old('use_version') ? "checked" : null }}>
                            <label for="use_version"></label>
                        </div>
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