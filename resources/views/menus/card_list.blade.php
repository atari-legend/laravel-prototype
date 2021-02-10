<div class="card bg-dark mb-4">
    <div class="card-header text-center">
        <h2 class="text-uppercase">Menus</h2>
    </div>
    <div class="card-body p-2">
        <p class="card-text">
            Welcome to the menus section! This is the place to find all the
            menus of your favorite Atari ST crews. There are currently
            {{ count($menusets) }} menu sets in the database.
        </p>
    </div>
    <div class="card-body p-2">
        <div class="row mb-2 text-center">
            <ul class="list-inline menu-index">
                <li class="list-inline-item mx-0 my-1 active">
                    <a href="#" class="m-1" data-isotope-filter="*">All</a>
                </li>
                @foreach (range('A', 'Z') as $letter)
                    <li class="list-inline-item mx-0 my-0">
                        <a href="#" class="m-1" data-isotope-filter=".letter-{{ Str::lower($letter) }}">{{ $letter }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="isotope">
            @foreach ($menusets as $set)
                <div class="col-12 col-sm-6 col-md-4 p-2 letter-{{ Str::lower(Str::substr($set->name, 0, 1)) }}">
                    <div class="d-flex bg-darklight border border-primary-dark">
                        <div class="bg-black text-center py-3 fs-1 text-audiowide menuset-letter">{{ Str::upper(Str::substr($set->name, 0, 1)) }}</div>
                        <div class="w-100 px-1 py-2">
                            <div class="text-center">
                                <a href="{{ route('menus.show', [$set]) }}">{{ $set->name }}</a><br>
                                <div class="text-muted">{{ $set->menus->count() }} {{ Str::plural('menu', $set->menus->count()) }}</div>

                                @if ($set->crews->count() > 1 || $set->crews->first()->crew_name !== $set->name)
                                    <div class="mt-2">
                                        <small><span class="text-muted">by</span> {{ $set->crews->pluck('crew_name')->join(', ')}}</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>