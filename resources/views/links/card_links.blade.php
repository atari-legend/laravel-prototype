<div class="card bg-dark mb-4">
    <div class="card-header text-center">
        <h2 class="text-uppercase">Links</h2>
    </div>

    <div class="card-header p-2">
        <p class="card-text">
            <ul class="list-unstyled">
                <li class="w-45 d-inline-block">
                    <a href="{{ route('links.index') }}" class="{{ isset($category) ? '' : 'font-weight-bold text-white' }}">All</a>
                </li>
                @foreach ($categories as $c)
                    <li class="w-45 d-inline-block">
                        @if ($c->websites->count() > 0)
                            <a href="{{ route('links.index', ['category' => $c]) }}"
                                class="{{ (isset($category) && $category->website_category_id === $c->website_category_id) ? 'font-weight-bold text-white' : '' }}">{{ $c->website_category_name }}</a>
                        @else
                            <span class="text-muted">{{ $c->website_category_name }}</span>
                        @endif
                        <small class="text-muted">({{ $c->websites->count() }})</small>
                    </li>
                @endforeach
            </ul>
        </p>
    </div>

    <div class="card-body p-0 striped">
        @foreach ($websites as $website)
            <div class="row g-0 p-2">
                <div class="col-md-4">
                    @if ($website->website_imgext)
                        <img class="w-100" src="{{ asset('storage/images/website_images/'.$website->file) }}">
                    @endif
                </div>
                <div class="col-md-8 pl-2">
                    @if ($website->inactive === 1)
                        <small class="text-warning mt-1 float-right"><i class="fas fa-exclamation-triangle"></i> Appears to be inactive</small>
                    @endif

                    <h4 class="card-title"><a href="{{ $website->website_url }}">{{ $website->website_name }}</a></h4>
                    <h6 class="card-subtitle text-muted">Added on {{ date('F j, Y', $website->website_date) }} by {{ Helper::user($website->user) }}</h6>
                    <div class="mb-2"><small><a href="{{ $website->website_url }}">{{ $website->website_url }}</a></small></div>
                    <p class="card-text">{{ $website->description }}</p>
                </div>
            </div>
        @endforeach

        {{ $websites->withQueryString()->links() }}
    </div>
</div>