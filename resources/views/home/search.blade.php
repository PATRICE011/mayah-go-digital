<div class="search" id="search">
   
    <form action="{{ route('searchProduct') }}" method="GET" class="search__form">
        <i class="ri-search-line search__icon"></i>
        <input type="search" name="search" placeholder="What are you looking for?" class="search__input" value="{{ request('search') }}">
    </form>
    
    <i class="ri-close-line search__close" id="search-close"></i>
    <!-- @if (isset($error))
        <p class="search__error">{{ $error }}</p>
    @endif -->
</div>
