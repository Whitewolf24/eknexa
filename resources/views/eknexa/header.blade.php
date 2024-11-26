<!DOCTYPE html>
<html>

<header>
    <img id="logo" src="{{ asset('storage/images/logo.png') }}" width="260" height="80" alt="StinPlateia" />

    <button id="add">
        <a href="{{ route('create') }}">+</a> <!-- Use route() for proper routing in Laravel -->
    </button>
</header>


</html>