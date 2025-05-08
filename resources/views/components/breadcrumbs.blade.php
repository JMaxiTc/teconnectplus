<nav aria-label="breadcrumb">
    <ol class="breadcrumb styled-breadcrumb">
        @foreach($breadcrumbs as $name => $url)
            @if($loop->first)
                <li class="breadcrumb-item">
                    <a href="{{ $url }}">
                        <i class="bi bi-house-door-fill"></i> {{ $name }}
                    </a>
                </li>
            @elseif($loop->last)
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $name }}
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $url }}">{{ $name }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
