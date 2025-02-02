<header>
  <div class="m-4 flex justify-between items-end lg:order-2">
    <!-- الجزء الخاص بالشعار في أقصى اليمين مع تأثير الدوران -->
    <div class="flex justify-end items-center">
      <a href="/" class="flex items-center">
        <div class="mr-3 h-6 sm:h-9 rotate" alt="مدار">
          <x-logo href="/" showText="true" />
        </div>
        <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">مدار</span>
      </a>
    <li class="flex items-center px-4">
      <a href="{{ route('companies.index') }}" class="p-0 transition-all text-sm ease-nav-brand text-slate-500 dark:text-white">
        <i class="cursor-pointer fa fa-cog" aria-hidden="true"></i>
      </a>
    </li>
  </div>

  <!-- الجزء الخاص بالروابط والإعدادات في أقصى اليسار -->
  <div class="flex justify-start items-center lg:order-2">
    <ul class="flex flex-row justify-start pl-0 mb-0 list-none md-max:w-full">
      <li class="flex items-center">
        @auth
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block px-0 py-2 font-semibold transition-all ease-nav-brand text-sm text-slate-500 dark:text-white">
              <i class="fa fa-sign-out sm:mr-1" aria-hidden="true"></i>
              <span class="hidden sm:inline">Sign Out</span>
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="block px-0 py-2 font-semibold transition-all ease-nav-brand text-sm text-slate-500 dark:text-white">
            <i class="fa fa-user sm:mr-1" aria-hidden="true"></i>
            <span class="hidden sm:inline">Sign In</span>
          </a>
        @endauth
      </li>
    
      <div class="flex items-center pr-6">
        <button id="theme-toggle" type="button" class="text-gray-900 dark:text-white">
          <i class="fas fa-moon"></i>
        </button>
      </div>
    
      <!-- زر القائمة المنسدلة (الهامبورجر) للموبايل -->
      <button data-collapse-toggle="mobile-menu-2" type="button" class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="mobile-menu-2" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
        <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
      </button>
    </ul>
  </div>
  </div>

  <!-- القائمة المنسدلة -->
  <div class="max-w-screen-xl flex flex-wrap justify-between items-center mx-auto">
    <div class="hidden justify-between items-center w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
      <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-4 lg:mt-0">
        @foreach ($NavbarLinks as $menu)
          <li class="relative group">
            <a href="#" class="block py-2 pr-4 pl-3 text-xs text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700 transition-transform transform hover:scale-110">
              {{ $menu['text'] }}
            </a>
            @if (isset($menu['children']) && count($menu['children']) > 0)
              <ul class="absolute z-50 left-0 hidden w-48 bg-white shadow-lg dark:bg-gray-800 group-hover:block">
                @foreach ($menu['children'] as $child)
                  <li>
                    <a href="{{ $child['href'] }}" class="block px-4 py-2 text-xs text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600">
                      <i class="{{ $child['icon'] }}"></i> {{ $child['text'] }}
                    </a>
                  </li>
                @endforeach
              </ul>
            @endif
          </li>
        @endforeach
      </ul>
    </div>
  </div>
</header>

<script>
// توجيه القائمة المنسدلة عند النقر عليها في الهاتف المحمول
document.querySelectorAll('li.relative > a').forEach(item => {
  item.addEventListener('click', function(event) {
    const submenu = this.nextElementSibling;
    if (submenu && submenu.classList.contains('hidden')) {
      submenu.classList.remove('hidden');
    } else if (submenu) {
      submenu.classList.add('hidden');
    }
  });
});
</script>
