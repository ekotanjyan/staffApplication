@if (!empty($breadcrumbs))
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">
                    <i class="czi-home"></i>Home
                </a>
            </li>
            @foreach ($breadcrumbs as $label => $link)
                <li class="breadcrumb-item @if($loop->last) active @endif" @if($loop->last)aria-current="page"@endif>
                    @if (is_int($label) && ! is_int($link))
                        {{ $link }}
                    @else
                        @if($loop->last)
                            {{ $label }}
                        @else
                            <a href="{{ $link }}">{{ $label }}</a>
                        @endif
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
