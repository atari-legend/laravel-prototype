<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Game;
use App\Review;
use App\ReviewScore;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $authors = User::has('reviews')
            ->get();

        $reviews = Review::where('review_edit', Review::REVIEW_PUBLISHED);

        if ($request->filled('author')) {
            $reviews->whereHas('user', function (Builder $query) use ($request) {
                $query->where('user_id', $request->input('author'));
            });
        }

        $reviews = $reviews
            ->orderByDesc('review_date')
            ->paginate(5);

        return view('reviews.index')
            ->with([
                'reviews' => $reviews,
                'authors' => $authors,
            ]);
    }

    public function show(Review $review)
    {
        $otherReviews = collect([]);

        if (isset($review->user)) {
            $otherReviews = Review::where('user_id', $review->user->user_id)
                ->where('review_edit', Review::REVIEW_PUBLISHED)
                ->where('review_id', '!=', $review->review_id)
                ->get();
        }

        return view('reviews.show')
            ->with([
                'review'       => $review,
                'otherReviews' => $otherReviews,
            ]);
    }

    public function prepareSubmit(Request $request)
    {
        if (!$request->filled('game')) {
            return response(400);
        }

        $game = Game::find($request->game);

        return view('reviews.submit')
            ->with([
                'game'  => $game,
            ]);
    }

    public function submit(Request $request)
    {
        $game = Game::find($request->game);

        $review = new Review();
        $review->review_text = $request->text;
        $review->review_date = time();
        $review->review_edit = Review::REVIEW_UNPUBLISHED;

        $request->user()->reviews()->save($review);
        $game->reviews()->save($review);

        $score = new ReviewScore();
        $score->review_graphics = $request->graphics ?? 0;
        $score->review_sound = $request->sound ?? 0;
        $score->review_gameplay = $request->gameplay ?? 0;
        $score->review_overall = $request->overall ?? 0;

        $review->score()->save($score);

        $request->session()->flash('alert-title', 'Review submitted');
        $request->session()->flash(
            'alert-success',
            'Thanks for your submission, a moderator will review it soon!'
        );

        return redirect()->route('games.show', [$game]);
    }

    public function postComment(Review $review, Request $request)
    {
        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->timestamp = time();

        $request->user()->comments()->save($comment);
        $review->comments()->save($comment);

        return back();
    }
}
