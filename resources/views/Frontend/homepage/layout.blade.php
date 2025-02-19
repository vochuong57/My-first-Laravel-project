<!DOCTYPE html>
<html lang="en">
    @include('Frontend.component.head')
    <body>
        @include('Frontend.component.header')
        @yield('content')
        @include('Frontend.component.footer')
        @if(isset($popup) && $popup == true)
            @include('Frontend.component.popup')
        @endif
        @include('Frontend.component.script')
    </body>
</html>