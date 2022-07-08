<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
@extends('layouts.app')

@section('content')    
<div class="ads-detail">
        <div id="hideMe" class="error-msg">
            {{ session('picture_error') }}
        </div>
        @if(Auth::user() !== NULL && Auth::user()->id == $ad->author_id)
        <form action="{{ route('ads.update', $ad->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="author_id" name="author_id" value="{{ $ad->author_id }}">
            <p>
                <label for="title">Youre ad's title</label>
                <input type="text" id="title" name="title" value="{{ $ad->title }}" required>
            </p>
            <p>
                <label for="title">Youre ad's description</label>
                <!-- <input type="text" id="description" name="description" value="{{ $ad->description }}"> -->
                <textarea id="description" name="description" value="" required>{{ $ad->description }}</textarea>
            </p>
            <p>
            <label for="category">Your ad's category</label>
                <select name="category" id="category" required>
                    <option value="" selected disabled hidden>Choose a category</option>
                    @if($ad->category == 'sell')
                        <option value="sell" selected>
                    @else
                        <option value="sell">
                    @endif Sell</option>
                    @if($ad->category == 'buy')
                        <option value="buy" selected>
                    @else
                        <option value="buy">
                    @endif Buy</option>
                    @if($ad->category == 'found')
                        <option value="found" selected>
                    @else
                        <option value="found">
                    @endif Found</option>
                    @if($ad->category == 'lost')
                        <option value="lost" selected>
                    @else
                        <option value="lost">
                    @endif Lost</option>
                </select>
            </p>
            <p>
                <label for="location">Your ad's location</label>
                <input type="text" id="location" name="location" value="{{ $ad->location }}" required>
            </p>      
            <p>
                <label for="price">Your ad's price</label>
                <input type="number" id="price" name="price" min=0 step=0.01 value="{{ $ad->price }}" required>
            </p>

            <div class="pic-wrapper-edit">
                <p class="hidden">{{ $i=0 }}</p>
                @foreach(explode(",", $ad->picture) as $picture)
                <p class="hidden">{{ $i++ }}</p>
                <div class="ad-pic">
                            <!-- Modal pour afficher l'image en détail -->
                            <button type="button" class="ad-pic-btn" data-toggle="modal" data-target="#myModal{{ $i }}">
                                <img src="../img/{{ $picture }}" class="ad-pic-detail" alt="ad's picture">
                            </button>
                            <div id="myModal{{ $i }}" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <img src="../img/{{ $picture }}" alt="ad's picture">
                                    </div>
                                </div>
                            </div>           
                </div>
                @endforeach    
          </div>  

            <p class="pic-mngt">
                <label class="pic-label" for="pictures">Picture(s)(one or more, replace current ones)</label>
                <p><input type="file" id="pictures" name="pictures[]" multiple></p>
                <p class='pic-format'>Format supported: jpg, jpeg, png</p>
                <p class='pic-size'>Max size: 2Mo</p>
            </p>

            <p><button class="btn btn-warning" type="submit" id="update" name="update">Update ad</button></p>
        </form>
        <!-- Delete button without modal -->
        <!-- <form class="form-delete-ad" action="{{ route('ads.destroy', $ad->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit" id="delete" name="delete">Delete ad</button>
        </form> -->

        <!-- Delete button with modal -->
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteAdModal">
            Delete ad
        </button>
        <div id="deleteAdModal" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete this ad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You're about to delete your ad. Are you sure ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                        <form class="form-delete-ad" action="{{ route('ads.destroy', $ad->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" id="delete" name="delete">Delete ad</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>     
        @else
        <!-- Display mode -->
        <div class="detail-wrapper">
            <div class="pic-wrapper">
            <p class="hidden">{{ $i=0 }}</p>
            @foreach(explode(",", $ad->picture) as $picture)
                <p class="hidden">{{ $i++ }}</p>
                <div class="ad-pic">
                        <!-- Modal pour afficher l'image en détail -->
                        <button type="button" class="ad-pic-btn" data-toggle="modal" data-target="#myModal{{ $i }}">
                            <img src="../img/{{ $picture }}" class="ad-pic-detail" alt="ad's picture">
                        </button>
                        <div id="myModal{{ $i }}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <img src="../img/{{ $picture }}" alt="ad's picture">
                                </div>
                            </div>
                        </div>           
                </div>
                @endforeach    
            </div>
            <div class="detail-wrapper-info">
                <h1>{{ $ad->title }}</h1>
                <p>{{ $ad->description }}</p>
                <!-- <p>{{ $ad->category }}</p> -->
                <hr>
                <p><span>Location: </span>{{ $ad->location }}</p>
                <p>
                    @if( $ad->category == 'buy' || $ad->category == 'sell')
                        <span>Price: </span>
                    @else
                        <span>Offer: </span>
                    @endif
                    @if ($ad->price != floor($ad->price))
                        {{ number_format($ad->price, 2, ',', ' ') }} €
                    @else
                        {{ number_format($ad->price, 0, ',', ' ') }} €
                    @endif
                </p>
                <p>
                    <p><span>Interested ?<span></p>    
                    <a class="contact_author" href="mailto:{{ $ad->user->email }}?Subject=StarAds - Someone is interested by your ad &Body=I saw your add and I'd like to have more details about it.%0D%0A%0D%0AYour ad: {{ $ad->title }}%0D%0ALink: https://www.starads.com/ads/{{ $ad->id }}">
                    <i class="bi bi-envelope"></i> {{ $ad->user->name}}
                    </a>
                </p>
            </div>
        </div>
        @endif

        <div class="share">
            <p>Share this ad across the galaxy. No comlink ? No problem, our astrodroids can also use : </p>
            <div class="social">
                <div class="share-twitter">
                    <a class="twitter-share-button" href="https://twitter.com/intent/tweet?text=I saw this and thought you would like it&url=https://www.starads.com/ads/{{ $ad->id }}&hashtags=StarAds" target="_blank">
                        <i class="bi bi-twitter"></i>
                    </a>
                </div>
                <div class="share-facebook">
                    <a href="https://www.facebook.com/sharer.php?u=https://www.starads.com/ads/{{ $ad->id }}" target="_blank">
                        <i class="bi bi-facebook"></i>
                    </a>
                </div>
                <div class="share-email">
                <a href="mailto:?Subject=StarAds is awesome&Body=I saw this and thought you would like it : https://www.starads.com/ads/{{ $ad->id }}">
                    <i class="bi bi-envelope"></i>
                </a>
                </div>
</div>
        </div>
</div>

    <p class="back-lnk"><a href="{{ route('ads.index') }}"><i class="bi bi-box-arrow-in-left"></i> Back</a></p>   
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>