<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Bizboo')
            <img src="{{ asset('assets/logo.png') }}" class="logo" alt="Logo">
            @else
            {{ $slot }}
            @endif
        </a>
    </td>
</tr>