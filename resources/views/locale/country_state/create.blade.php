@if ($errors->has('create.*'))

@push('scripts')

<script type="text/javascript">
    $(window).on('load', function () {
        $('#countryStateModal').modal('show');
    });
</script>

@endpush

@endif

<div class="modal fade show" id="countryStateModal" tabindex="-1" role="dialog" aria-labelledby="countryStateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="countryStateModal">{{ __('modules.create', ['module' => trans_choice('modules.country_state', 1)]) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('locale.country-states.store') }}" method="POST" role="form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="create_name" class="col-form-label">{{ __('labels.name') }}</label>
                        <input type="text" id="create_name" name="create[name]" value="{{ old('create.name') }}" class="form-control ucfirst @error('create.name') is-invalid @enderror">
                        @error('create.name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <p class="text-center">-- Or --</p>

                    <div class="form-group">
                        <label for="create_file" class="col-form-label">{{ trans_choice('labels.upload_file', 1) }}</label>
                        <div class="custom-file">
                            <input type="file" id="create_file" name="create[file]" class="custom-file-input @error('create.file') is-invalid @enderror">
                            <label class="custom-file-label" for="validatedCustomFile">Choose file</label>
                            @error('create.file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="icheck-purple">
                            <input type="checkbox" name="create[withCity]" id="create_withCity" {{ old('create.withCity') ? 'checked' : null }}>
                            <label for="create_withCity">{{ __('labels.with_city') }}</label>
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