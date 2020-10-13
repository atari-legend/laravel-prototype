<div class="card bg-dark mb-4 card-ellipsis">
    <div class="card-header text-center">
        <h2 class="text-uppercase"><a href="{{ route('reviews.index') }}">In-Depth Reviews</a></h2>
    </div>
    <div class="striped">
        @forelse ($reviews as $review)
            <div class="card-body p-2">
                <h3 class="card-title text-h6 text-audiowide"><a href="{{ route('games.show', ['game' => $review->games[0]->game_id]) }}">{{ $review->games[0]->game_name }}</a></h3>
                <p class="card-subtitle text-muted mb-2">{{ date('F j, Y', $review->review_date) }} by {{ Helper::user($review->user) }}</p>
                <p class="card-text ellipsis">
                    {!! Helper::bbCode(Helper::extractTag($review->review_text, "frontpage")) !!}
                </p>
                <a class="d-block text-right" href="{{ route('reviews.show', ['review' => $review->review_id]) }}">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        @empty
            No reviews!
        @endforelse
    </div>
</div>
