<div class="card bg-dark mb-4">
    <div class="card-header text-center">
        <h2 class="text-uppercase">Games</h2>
    </div>
    <div class="card-body p-2">
        <p class="card-text">
            Welcome to the game section. The place to be for every Atari ST and retro
            gaming nutcase. Enjoy your stay! Search for a game by using any of the
            functionalities below. Combinations are allowed. There are currently {{ $gamesCount }}
            games in the database. Check out the statistics on the database in the 'Statistics'
            card! If there is data missing and you are willing to contribute, please get in touch with
            the team.
        </p>
    </div>
    <div class="card-body p-2">
        <div class="row-mb-4 text-center">
            <ul class="list-inline">
                <li class="list-inline-item mx-0 my-1">
                    <a href="{{ route('games.search', ['titleAZ' => '0-9']) }}" class="m-1">#</a>
                </li>
                @foreach (range('A', 'Z') as $letter)
                    <li class="list-inline-item mx-0 my-0">
                        <a href="{{ route('games.search', ['titleAZ' => $letter]) }}" class="m-1">{{ $letter }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <form method="get" action="{{ route('games.search') }}">
            <div class="row mb-3">
                <label for="titleAZ" class="col-3 col-form-label">Title (A-Z)</label>
                <div class="col">
                    <select class="form-select" id="titleAZ" name="titleAZ">
                        <option selected value="">-</option>
                        <option value="0-9">0-9</option>
                        @foreach (range('A', 'Z') as $letter)
                            <option value="{{ $letter }}">{{ $letter }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="title" class="col-3 col-form-label">Title</label>
                <div class="col position-relative">
                    <input type="text" class="autocomplete form-control"
                        data-autocomplete-endpoint="{{ URL::to('/ajax/games.json/') }}"
                        data-autocomplete-key="game_name"
                        id="title" name="title" autocomplete="off">
                </div>
            </div>
            <div class="row mb-3">
                <label for="publisher" class="col-3 col-form-label">
                    Publisher
                    <a href="#" data-dropdown-toggle="publisher,publisher_id"><i class="fas fa-chevron-circle-down"></i></a>
                </label>
                <div class="col position-relative">
                    <input type="text" class="autocomplete form-control"
                        data-autocomplete-endpoint="{{ URL::to('/ajax/companies.json') }}"
                        data-autocomplete-key="pub_dev_name"
                        id="publisher" name="publisher" autocomplete="off">
                    <select class="form-select d-none" id="publisher_id" name="publisher_id">
                        <option value="">-</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->pub_dev_id }}">{{ $company->pub_dev_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="developer" class="col-3 col-form-label">
                    Developer
                    <a href="#" data-dropdown-toggle="developer,developer_id"><i class="fas fa-chevron-circle-down"></i></a>
                </label>
                <div class="col position-relative">
                    <input type="text" class="autocomplete form-control"
                        data-autocomplete-endpoint="{{ URL::to('/ajax/companies.json') }}"
                        data-autocomplete-key="pub_dev_name"
                        id="developer" name="developer" autocomplete="off">
                    <select class="form-select d-none" id="developer_id" name="developer_id">
                        <option value="">-</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->pub_dev_id }}">{{ $company->pub_dev_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="year" class="col-3 col-form-label">
                    Release year
                    <a href="#" data-dropdown-toggle="year,year_id"><i class="fas fa-chevron-circle-down"></i></a>
                </label>
                <div class="col position-relative">
                    <input type="text" class="autocomplete form-control"
                        data-autocomplete-endpoint="{{ URL::to('/ajax/release-years.json') }}"
                        data-autocomplete-key="year"
                        id="year" name="year" autocomplete="off">
                    <select class="form-select d-none" id="year_id" name="year_id">
                        <option value="">-</option>
                        @foreach ($years as $year)
                            <option value="{{ $year->year }}">{{ $year->year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="genre" class="col-3 col-form-label">
                    Genre
                    <a href="#" data-dropdown-toggle="genre,genre_id"><i class="fas fa-chevron-circle-down"></i></a>
                </label>
                <div class="col position-relative">
                    <input type="text" class="autocomplete form-control"
                        data-autocomplete-endpoint="{{ URL::to('/ajax/genres.json') }}"
                        data-autocomplete-key="name"
                        id="genre" name="genre" autocomplete="off">
                    <select class="form-select d-none" id="genre_id" name="genre_id">
                        <option value="">-</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-3 col-form-label"></div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="download">
                        <label class="form-check-label" for="download">
                            Download
                        </label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-3 col-form-label"></div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="screenshot" name="screenshot">
                        <label class="form-check-label" for="screenshot">
                            Screenshot
                        </label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-3 col-form-label"></div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="boxscan" name="boxscan">
                        <label class="form-check-label" for="boxscan">
                            Boxscan
                        </label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-3 col-form-label"></div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="review" name="review">
                        <label class="form-check-label" for="review">
                            Review
                        </label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-3 col-form-label"></div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="export" name="export">
                        <label class="form-check-label" for="export">
                            Export mode
                        </label>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>
</div>
