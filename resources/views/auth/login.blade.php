<x-base>

    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <a href="#" class="flex items-center p-4 mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <dev class="w-8 h-8 ml-2 ">
                    <x-logo href="/" showText="true" />
                </dev>
            </a>
            <div class="w-full bg-white rounded-lg shadow  border border-gray-200 dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        تسجيل الدخول إلى حسابك
                    </h1>
                    <form id="loginForm" class="space-y-4 md:space-y-6" action="{{ route('login.submit') }}" method="POST">
                        @csrf
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">كلمة المرور</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-start">

                            </div>
                            <a href="{{ route('password.change') }}" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">نسيت كلمة المرور؟</a>
                        </div>
                        
                        <button type="submit" class="w-full  border border-gray-300 text-gray-900  bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">تسجيل الدخول</button>
                        
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-base>
<script>
    if (themeToggle) {
    const icon = themeToggle.querySelector('i');
    const savedTheme = localStorage.getItem('theme') || 'light';

    function applyTheme(theme) {
        const isDark = theme === 'dark';
        document.documentElement.classList.toggle('dark', isDark);
        localStorage.setItem('theme', theme);
        icon.classList.toggle('fa-moon', !isDark);
        icon.classList.toggle('fa-sun', isDark);
    }

    applyTheme(savedTheme);

    themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
        applyTheme(currentTheme);
    });
}


</script>