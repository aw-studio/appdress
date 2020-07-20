<ul>

    @foreach($items as $item)
        <li>
            @if($item->route())
                <a href="{{ $item->route() }}">{{ $item->getTitle() }}</a>
            @else
                <a href="#">{{ $item->getTitle() }}</a>
            @endif
            @if(!empty($item->getChildren())) 
                @include('docs::partials.nav_main_items', ['items' => $item->getChildren()])
            @endif
        </li>
    @endforeach
        
    

</ul>