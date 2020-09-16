<div class="card bg-dark mb-4">
    <div class="card-header text-center">
        <h2 class="text-uppercase">{{ $article->texts->first()->article_title }}</h2>
    </div>

    <div class="card-body p-2 bg-darklight">
        <h5>Written by {{ Helper::user($article->user) }}</h5>
        <span class="text-muted">{{ date('F j, Y', $article->texts->first()->article_date) }}</span>
    </div>

    <div class="card-body p-2 bg-darklight">
        <div class="row g-0">
            <div class="col-9">
                {!! Helper::bbCode(nl2br($article->texts->first()->article_text)) !!}
            </div>
            <div class="col-3 pl-2 text-center text-muted">
                @foreach ($article->screenshots as $screenshot)
                    <div class="bg-dark">
                        <img class="w-100 " src="{{ asset('storage/images/article_screenshots/'.$screenshot->screenshot->file) }}">
                        <p class="pb-5 mb-0">{{ $screenshot->comment->comment_text }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>