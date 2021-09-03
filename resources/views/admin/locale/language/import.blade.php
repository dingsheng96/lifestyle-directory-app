@if ($errors->has('create.*'))

@push('scripts')

<script type="text/javascript">
    $(window).on('load', function () {
        $('#importTranslationModal').modal('show');
    });
</script>

@endpush

@endif

<div class="modal fade show hide" id="importTranslationModal" tabindex="-1" role="dialog" aria-labelledby="importTranslationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importTranslationModalLabel">{{ __('labels.import_translation') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.locales.languages.translations.import', ['language' => $language->id]) }}" method="POST" role="form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <div class="form-group row">
                        <label for="version" class="col-form-label col-sm-2">{{ __('labels.version') }}</label>
                        <div class="col-sm-10">
                            <input id="version" name="version" class="form-control-plaintext" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="file" class="col-form-label col-sm-2">{{ trans_choice('labels.upload_file', 1) }}</label>
                        <div class="col-sm-10">
                            <input type="file" id="file" name="file" class="form-control-file @error('file') is-invalid @enderror">
                            @error('file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <ul class="pl-3 mt-3">
                                {!! trans_choice('messages.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG']) !!}
                            </ul>
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