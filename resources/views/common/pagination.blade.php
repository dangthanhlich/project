@if ($paginator->total() >= 1)
    <div class="card-footer pagination-border-box" id="pagination-box">
        <div class="float-left">
            {{ $paginator->total() }} 件中 {{ $paginator->firstItem() }} 件から {{ $paginator->lastItem() }} 件まで表示
        </div>
        <ul class="pagination pagination-sm m-0 float-right ">
            <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url(1) }}">先頭</a>
            </li>

            <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->currentPage()-1) }}">前</a>
            </li>

            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active not-click' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->currentPage()+1) }}">次</a>
            </li>

            <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">最終</a>
            </li>
        </ul>
    </div>
@endif
