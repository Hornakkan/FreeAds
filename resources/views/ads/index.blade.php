@extends('layouts.app')

@section('content')
    <!-- <h2>Ads list</h2> -->
    <div class="wrapper">
        <aside class="filters">
            <!-- Menu des filtres -->
            <div class="filters-menu">
                <form action="{{ route('ads.index') }}" method="get" class="filters-menu-search">
                @csrf
                @if (isset($_GET['search']))
                    <input type="search" class="search-bar" id="search" name="search" value="{{ $_GET['search'] }}">
                @elseif (isset($_GET['filter_search']))
                    <input type="search" class="search-bar" id="search" name="search" value="{{ $_GET['filter_search'] }}">
                @else
                    <input type="search" class="search-bar" id="search" name="search" placeholder="search">
                @endif
                    <button type="submit" class="btn btn-warning" id="search_btn" name="search_btn" aria-label="search button">
                        <!-- Search ads -->
                        <i class="bi bi-search" alt="search button"></i>
                    </button>
                </form>
                <form action="{{ route('ads.index') }}" method="get" class="filters-menu-filter">
                @if (isset($_GET['search']))                    
                    <input type="hidden" id="filter_search" name="filter_search" value="{{ $_GET['search'] }}">
                @elseif (isset($_GET['filter_search']))
                    <input type="hidden" id="filter_search" name="filter_search" value="{{ $_GET['filter_search'] }}">
                @else
                    <input type="hidden" id="filter_search" name="filter_search" value="">
                @endif
                @if (isset($_GET['author_id']))                    
                    <input type="hidden" id="filter_author_id" name="filter_author_id" value="{{ $_GET['author_id'] }}">
                @elseif (isset($_GET['filter_author_id']))
                    <input type="hidden" id="filter_author_id" name="filter_author_id" value="{{ $_GET['filter_author_id'] }}">
                @else
                    <input type="hidden" id="filter_author_id" name="filter_author_id" value="">
                @endif                      
                <p class="filters-menu-label">Filter search results by</p>                 
                    
                        <label for="filter_category">Category</label>
                        <select name="filter_category" id="filter_category">
                            <option value="" selected disabled hidden>Choose a category</option>
                            @if(isset($_GET['filter_category']) and $_GET['filter_category'] == 'sell')
                                    <option class="test" value="sell" selected>
                                @else
                                    <option class="test" value="sell">
                                @endif Sell</option>
                                @if(isset($_GET['filter_category']) and $_GET['filter_category'] == 'buy')
                                    <option value="buy" selected>
                                @else
                                    <option value="buy">
                                @endif Buy</option>
                                @if(isset($_GET['filter_category']) and $_GET['filter_category'] == 'found')
                                    <option value="found" selected>
                                @else
                                    <option value="found">
                                @endif Found</option>
                                @if(isset($_GET['filter_category']) and $_GET['filter_category'] == 'lost')
                                    <option value="lost" selected>
                                @else
                                    <option value="lost">
                                @endif Lost</option>
                        </select>
 
                        <label for="filter_location">Location</label>
                        @if(isset($_GET['filter_location']) && !empty($_GET['filter_location']))
                            <input type="text" id="filter_location" name="filter_location" value="{{ $_GET['filter_location'] }}">
                        @else
                            <input type="text" id="filter_location" name="filter_location"  placeholder="location">
                        @endif

                        <label for="filter_price">Price <span>(Republic credits are no good out here)</span></label>
                        @if(isset($_GET['filter_price']) && !empty($_GET['filter_price']))
                            <input type="number" id="filter_price" name="filter_price" value="{{ $_GET['filter_price'] }}" min=0 step=0.01>
                        @else
                            <input type="number" id="filter_price" name="filter_price" placeholder="lower than ..." min=0 step=0.01>
                        @endif

                    <p class="p_filter_btn"><button type="submit" class="btn btn-warning" id="filter_btn" name="filter_btn">Filter ads</button></p>
                </form>
                <form action="{{ route('ads.index') }}" method="" class="filters-menu-reset">
                    <p class="p_reset_filter_btn"><button class="btn btn-warning" id="reset_filter_btn" name="reset_filter_btn">Reset filters</button><p>
                </form>
                @if(Auth::user() !== NULL)
                <hr>
                <form action="{{ route('ads.index') }}" method="get" class="filters-menu-my">
                    <input type="hidden" @if (!empty(Auth::user())) value="{{ Auth::user()->id }}" @endif id="author_id" name="author_id">
                    <p class="p_my_ads_btn"><button class="btn btn-warning" id="my_ads_btn" name="my_ads_btn">Get my Ads</button><p>
                </form>
                @endif
            </div>
        </aside>       
        <div class="ad-list">
            <!-- Liste des annonces -->
            <div id="hideMe" class="success-msg">
                {{ session('success') }}
            </div>            
            <ul>
            @foreach($ads as $ad)
                <li>      
                    <div class="card">
                        <div class="card-img">
                            <img class="card-img-top" src="img/{{ explode(',', $ad->picture)[0] }}" alt="Ad's picture">
                            <p class="card-img-price">
                                @if ($ad->price != floor($ad->price))
                                    {{ number_format($ad->price, 2, ',', ' ') }} €
                                @else
                                    {{ number_format($ad->price, 0, ',', ' ') }} €
                                @endif
                                
                            </p>
                        </div>
                        <div class="card-body">
                            <h5 class="card-body-title">{{ $ad->title }}</h5>
                            <p class="card-body-description">{{ substr($ad->description, 0, 80) }}</p>
                            <p class="card-body-category">{{ $ad->category }}</p>
                            <p class="card-body-price">
                                @if ($ad->price != floor($ad->price))
                                    {{ number_format($ad->price, 2, ',', ' ') }} €
                                @else
                                    {{ number_format($ad->price, 0, ',', ' ') }} €
                                @endif
                            </p>
                            <p class="card-body-location"><span>At </span>{{ $ad->location }}</p>
                            <p class="card-body-author"><span>By</span> {{ $ad->user->name }}</p>
                            <a href="{{ route('ads.show', $ad->id) }}" class="btn btn-warning">View details</a>
                        </div>
                    </div>                
                </li>
            @endforeach
            </ul>
        </div> 
    </div>
    <div class="pagination-block">
        @if(!empty($ads->links()))
            {{ $ads->links('layouts.paginationlinks') }}
        @endif
    </div>
@endsection