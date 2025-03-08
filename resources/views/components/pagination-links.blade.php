<div class="mt-4">
    <nav aria-label="Page navigation example">
        <ul class="inline-flex -space-x-px text-sm">
            {{-- زر Previous --}}
            <li>
                <a href="{{ $paginator->previousPageUrl() }}" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">السابق</a>
            </li>
            
            {{-- حلقة لعرض أرقام الصفحات --}}
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                <li>
                    <a href="{{ $url }}" class="flex items-center justify-center px-3 h-8 leading-tight {{ $page == $paginator->currentPage() ? 'text-blue-600 border border-gray-300 bg-blue-50' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                        {{ $page }}
                    </a>
                </li>
            @endforeach
    
            {{-- زر Next --}}
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">التالي</a>
            </li>
        </ul>
    </nav>
</div>

