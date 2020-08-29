<div class="card bg-dark mb-4">
    <div class="card-header text-center">
        <h2 class="text-uppercase">{{ $review->games->first()->game_name }}</h2>
    </div>

    <div class="card-body p-2 bg-darklight">
        <h5>Written by {{ Helper::user($review->user) }}</h5>
        <span class="text-muted">{{ date('F j, Y', $review->review_date) }}</span>
    </div>
    <div class="card-body p-2 bg-darklight">
        <div class="row g-0">
            <div class="col-9">
                {!! Helper::bbCode(nl2br($review->review_text)) !!}
            </div>
            <div class="col-3 pl-2 text-center text-muted">
                @foreach ($review->screenshots as $screenshot)
                    <div class="bg-dark">
                        <img class="w-100 " src="{{ asset('public/images/game_screenshots/'.$screenshot->screenshot->file) }}">
                        <p class="pb-5 mb-0">{{ $screenshot->comment->comment_text }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card-body p-2 bg-darklight">

        <h6>Score</h6>

        <ul class="list-unstyled">
            <li>Graphics: {{ $review->score->review_graphics }}</li>
            <li>Sound: {{ $review->score->review_sound }}</li>
            <li>Gameplay: {{ $review->score->review_gameplay }}</li>
            <li>Overall: {{ $review->score->review_overall }}</li>
        </ul>
    </div>
</div>
