<!DOCTYPE html>
<html lang="en">
    @include('Frontend.component.head')
    <body>
        @include('Frontend.component.header')
        @yield('content')
        @include('Frontend.component.footer')
        @include('Frontend.component.popup')
        @include('Frontend.component.script')
    </body>
</html>