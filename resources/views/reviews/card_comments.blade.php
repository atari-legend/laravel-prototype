<div class="card bg-dark mb-4">
    <div class="card-header text-center">
        <h2 class="text-uppercase">Review Comments</h2>
    </div>

    <div class="card-body p-2">
        @guest
            <p class="card-text text-center text-danger">
                Please <a href="{{ route('login') }}">log in</a> to add your own comment to this review
            </p>
        @endguest

        @auth
        <form method="post" action="{{ route('review.comment', ['review' => $review]) }}" class="text-center">
            @csrf
            <textarea class="form-control" rows="5" name="comment" placeholder="Your comment here..." required></textarea>
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
        @endauth
    </div>
    <div class="striped">
        @foreach ($review->comments->sortByDesc("timestamp") as $comment)
            @include('components.cards.partial_comment', ['context' => 'review', 'id' => $review->getKey()])
        @endforeach
    </div>
</div>
