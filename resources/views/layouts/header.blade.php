<header class="mb-3 mb-sm-2">
    <!-- Center the logo on small screens, align left on large ones -->
    <div class="header-inner mx-auto text-center text-lg-left">
        <!-- Hide full size banner on small screens -->
        <img class="d-none d-sm-inline" src="{{ asset('images/'.request()->attributes->get('logos')->random()) }}" alt="Header banner">
        <!-- Show small banner on small screens -->
        <img class="d-inline d-sm-none mb-2 mb-sm-0" src="{{ asset('images/top_logo01_480.png') }}" alt="Header banner">
        <!-- Only show the right logo on large screens -->
        <img class="float-right d-none d-lg-inline" src="{{ asset('images/'.request()->attributes->get('rightLogos')->random() ) }}" alt="">

        <!-- Random sprite animation -->
        @php
            $animation = request()->attributes->get('animations')->random();
        @endphp
        <img id="header-sprite" @if (strpos($animation, '_vert') !== false) class="vertical" @endif src="{{ asset('images/'.$animation) }}" alt="">
    </div>
</header>
