<div class="search" id="search">
   
    <form action="{{ route('searchProduct') }}" method="GET" class="search__form">
        <i class="ri-search-line search__icon"></i>
        <input type="search" name="search" placeholder="What are you looking for?" class="search__input" value="{{ request('search') }}">
    </form>
    
    <i class="ri-close-line search__close" id="search-close"></i>
<<<<<<< HEAD
=======
   
>>>>>>> 49d8031bb7a2b73a36d608fa139f3b6cffe92565
</div>
