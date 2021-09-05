<div class="modal fade show hide" id="uploadMediaModal" tabindex="-1" role="dialog" aria-labelledby="uploadMediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadMediaModalLabel">{{ trans_choice('labels.upload_image', 2) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('merchant.media.store') }}" method="POST" role="form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <div class="dropzone" id="myDropzone" data-max-files="{{ $max_files }}" data-accepted-files=".jpg,.jpeg,.png" data-action="update">
                            <div class="dz-default dz-message">
                                <h1><i class="fas fa-cloud-upload-alt"></i></h1>
                                <h4>{{ __('messages.drag_and_drop') }}</h4>
                                <ul class="list-unstyled">{!! trans_choice('messages.upload_file_rules', 2, ['maxsize' => '10mb', 'extensions' => 'JPG,JPEG, PNG', 'maxfiles' => $max_files]) !!}</ul>
                            </div>
                        </div>
                        @error('files')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
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