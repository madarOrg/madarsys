<x-base>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-4 py-6 mx-auto sm:px-2 md:h-screen lg:py-0">
            <a href="#" class="flex items-center mb-6 text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
                <dev class="w-6 h-6 sm:w-8 sm:h-8 mr-2">
                    <x-logo href="/" showText="true" />
                </dev>
            </a>
            <div class="w-full bg-white rounded-lg shadow  border border-gray-200 dark:border sm:max-w-sm md:max-w-md xl:max-w-lg dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 sm:p-8 space-y-4 md:space-y-6">
                    <h1 class="text-lg sm:text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white text-right">
                        إنشاء حساب
                    </h1>

                    @if(session('success'))
                        <div class="alert alert-success text-xs sm:text-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger text-xs sm:text-sm">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="space-y-2 md:space-y-2" action="{{ route('signup.submit') }}" method="POST">
                        @csrf

                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">اسمك</label>
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="اسم المستخدم" required>
                        </div>

                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com" required>
                        </div>

                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">كلمة المرور</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                        </div>

                        <div>
                            <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">اختر الدور</label>
                            <select name="role" id="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                 
                        <button type="submit" class="w-full border border-gray-300 text-gray-900 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">إنشاء حساب</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-base>
