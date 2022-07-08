@if($paginator->hasPages())
<ul class="pagination">
  <!-- Previous Page Link -->
  @if($paginator->onFirstPage())
    <li class="page-item disabled"><a href="{{ $paginator->previousPageUrl() }}" class="page-link" aria-label="previous page"><span>prev.</span></a></li>
  @else
    <li class="page-item"><a href="{{ $paginator->previousPageUrl() }}" class="page-link" rel="prev" aria-label="previous page">prev.</a></li>
  @endif

  <!-- Pagination Elements Here -->
  @foreach($elements as $element)
       <!-- Make three dots -->
       @if(is_string($element))
          <li class="page-item disabled"><a  class="page-link" aria-label="page number link"><span>{{$element}}</span></a></li>
       @endif

       <!-- Links Array Here -->
       @if(is_array($element))
           @foreach($element as $page=>$url)

            @if($page == $paginator->currentPage())
                <li class="page-item active"><a href="{{ $url }}" class="page-link" aria-label="page number link"><span>{{ $page }}</span></a></li>
            @else
                  <li class="page-item"><a href="{{ $url }}" class="page-link" aria-label="page number link">{{ $page }}</a></li>
            @endif

           @endforeach
       @endif

  @endforeach

  <!-- Next Page Link -->
  @if($paginator->hasMorePages())
    <li class="page-item"><a href="{{ $paginator->nextPageUrl() }}" class="page-link" aria-label="next page"><span>next</span></a></li>
  @else
  <li class="page-item disabled"><a href="{{ $paginator->nextPageUrl() }}" class="page-link" aria-label="next page"><span>next</span></a></li>
  @endif
</ul>

@endif