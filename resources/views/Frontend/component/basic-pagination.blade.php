@if($model->hasPages())
    <ul class="pagination">
        @foreach($model->getUrlRange(1, $model->lastPage()) as $page => $url)
            <li class="page-item {{ ($page == $model->currentPage()) ? 'active' : '' }}"><a href="{{ $url }}">{{ $page }}</a></li>
        @endforeach
    </ul>
@endif