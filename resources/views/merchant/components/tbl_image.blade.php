<div class="table-responsive">

    <table id="tbl_image" class="table w-100 table-striped">

        <thead>
            <tr>
                <th scope="col">{{ __('#') }}</th>
                <th scope="col">{{ __('labels.type') }}</th>
                <th scope="col">{{ __('labels.items') }}</th>

                @if (isset($thumbnail) && $thumbnail)
                <th scope="col">{{ __('labels.thumbnail') }}</th>
                @endif

                @if (isset($action) && $action)
                <th scope="col">{{ __('labels.action') }}</th>
                @endif
            </tr>
        </thead>

        <tbody>

            @forelse ($images as $image)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $image->type }}</td>
                <td>
                    <a href="{{ $image->full_file_path }}" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-external-link-alt"></i>
                        {{ $image->original_filename }}
                    </a>
                </td>

                @if (isset($thumbnail) && $thumbnail)
                <td>
                    <div class="icheck-purple">
                        <input type="radio" name="thumbnail" id="thumbnail_{{ $loop->iteration }}" value="{{ $image->id }}" {{ (old('thumbnail') == $image->id || $image->is_thumbnail) ? 'checked' : null }}>
                        <label for="thumbnail_{{ $loop->iteration }}"></label>
                    </div>
                </td>
                @endif

                @if (isset($action) && $action)
                <td>
                    @include('merchant.components.btn_action', ['no_action' => $image->is_thumbnail, 'download' => ['route' => $image->full_file_path,'attribute' => 'download'], 'delete' => ['route' => route('merchant.media.destroy', ['media' => $image->id])]])
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ ((isset($thumbnail) && $thumbnail) && (isset($action) && $action)) ? 5 : (((isset($thumbnail) && $thumbnail) || (isset($action) && $action)) ? 4 : 3) }}" class="text-center">
                    {{ __('messages.no_records') }}
                </td>
            </tr>
            @endforelse

        </tbody>

    </table>

</div>