<div class="card bg-dark mb-4">
    <div class="card-header text-center">
        <a class="float-right" href="{{ route('feed') }}"><i class="fas fa-rss-square text-warning"></i></a>
        <h2 class="text-uppercase">Interviews</h2>
    </div>
    <div class="card-body p-2 mb-3">
        <p class="card-text">
            In this section we'll be talking to the VIP's of the industry. People
            that were in some way or another important in the history of the ST.
            We are anxious to fill this section with special conversations, preserving
            information about the long lost classics and the good ol' days. If you
            know of anybody, make sure to pass over the information to us. So for now,
            grab your VIP pass and enter the world of glitter and glamour! Enjoy
            the read. There are currently <strong>{{ $interviews->total() }}</strong>
            interviews in the AL database!
        </p>
    </div>

    <div class="card-body p-0 striped">
        @foreach ($interviews as $interview)
            <div class="p-2 lightbox-gallery">
                <div class="clearfix mb-2">
                    <h3 class="text-h4">
                        <a href="{{ route('interviews.show', ['interview' => $interview]) }}">
                            {{ $interview->individual->ind_name }}
                        </a>
                    </h3>
                    <p class="card-subtitle text-muted">{{ date('F j, Y', $interview->texts->first()->interview_date) }} by {{ Helper::user($interview->user) }}</p>
                </div>

                <div class="clearfix">
                    @if (isset($interview->individual->text->ind_imgext))
                        <a class="lightbox-link" href="{{ asset('storage/images/individual_screenshots/'.$interview->individual->ind_id.'.'.$interview->individual->text->ind_imgext) }}">
                            <img class="col-4 col-sm-3 float-left mt-1 mr-2 mb-1" src="{{ asset('storage/images/individual_screenshots/'.$interview->individual->ind_id.'.'.$interview->individual->text->ind_imgext) }}" alt="Picture of {{ $interview->individual->ind_name }}">
                        </a>
                    @else
                        <img class="col-4 col-sm-3 float-left mt-1 mr-2 mb-1" src="{{ asset('images/unknown.jpg') }}" alt="Placeholder image as there is no picture for the interviewee">
                    @endif

                    {!! Helper::bbCode($interview->texts->first()->interview_intro) !!}<br>
                    <a class="d-block text-right mt-2" href="{{ route('interviews.show', ['interview' => $interview]) }}">
                        More <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        @endforeach

        {{ $interviews->links() }}
    </div>
</div>
