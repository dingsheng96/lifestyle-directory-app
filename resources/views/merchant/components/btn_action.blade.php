@if (empty($no_action))

<a role="button" class="btn btn-outline-purple dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    {{ __('labels.action') }}
</a>
<div class="dropdown-menu dropdown-menu-right">

    {{-- view button --}}
    @isset($view)
    <a role="button" href="{{ $view['route'] ?? '#' }}" class="dropdown-item" @isset($view['attribute']) {!! $view['attribute'] !!} @endisset>
        <i class="fas fa-book-open mr-2 text-blue"></i>
        {{ __('labels.view') }}
    </a>
    @endisset

    {{-- update button --}}
    @isset($update)
    <a role="button" href="{{ $update['route'] ?? '#' }}" class="dropdown-item" @isset($update['attribute']) {!! $update['attribute'] !!} @endisset>
        <i class="fas fa-edit mr-2 text-purple"></i>
        {{ __('labels.edit') }}
    </a>
    @endisset

    {{-- upload button --}}
    @isset($upload)
    <a role="button" href="{{ $upload['route'] ?? '#' }}" class="dropdown-item" @isset($upload['attribute']) {!! $upload['attribute'] !!} @endisset>
        <i class="fas fa-upload mr-2 text-cyan"></i>
        {{ __('labels.upload') }}
    </a>
    @endisset

    {{-- download button --}}
    @isset($download)
    <a role="button" href="{{ $download['route'] ?? '#' }}" class="dropdown-item" @isset($download['attribute']) {!! $download['attribute'] !!} @endisset>
        <i class="fas fa-download mr-2 text-green"></i>
        {{ __('labels.download') }}
    </a>
    @endisset

    {{-- delete button --}}
    @isset($delete)
    <a role="button" href="{{ $delete['route'] ?? '#' }}" class="dropdown-item" onclick="event.preventDefault(); deleteAlert('{{ __('messages.confirm_question') }}', '{{ __('messages.delete_info') }}', '{{ $delete['route'] }}')" @isset($delete['attribute']) {!! $delete['attribute'] !!} @endisset>
        <i class="fas fa-trash mr-2 text-red"></i>
        {{ __('labels.delete') }}
    </a>
    @endisset

</div>

@endif