<x-base>
   <section class="bg-gray-50 dark:bg-gray-900 min-h-screen grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
     
     <!-- الصورة الجانبية - في المنتصف -->
     <div class="flex justify-center items-center p-6">
       <img src="/images/reset.jpg" alt="تغيير كلمة الدخول" class="w-full h-auto max-w-md rounded-lg shadow-lg">
     </div>
 
     <!-- نموذج تغيير كلمة المرور -->
     <div class="flex justify-center items-center">
       <div class="w-full max-w-md rounded-lg dark:border dark:bg-gray-800 dark:border-gray-700 p-6" dir="rtl">
         
         <!-- الشعار -->
         <div class="flex justify-start mb-4">
           <x-logo href="/" showText="true" />
         </div>
 
         <!-- العنوان -->
         <h2 class="mb-4 text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white text-right">
           تغيير كلمة المرور
         </h2>
 
         <!-- النموذج -->
         <form class="space-y-4" action="{{ route('password.update') }}" method="POST">
           @csrf
 
           <div>
             <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
               البريد الإلكتروني
             </label>
             <input type="email" name="email" id="email"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
               placeholder="your@example.com" required>
           </div>
 
           <div>
             <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
               كلمة المرور الجديدة
             </label>
             <input type="password" name="password" id="password"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
               placeholder="••••••••" onfocus="this.placeholder=''" onblur="this.placeholder='••••••••'" required>
             @error('password')
               <span class="text-red-500 text-sm">{{ $message }}</span>
             @enderror
           </div>
 
           <div>
             <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
               تأكيد كلمة المرور الجديدة
             </label>
             <input type="password" name="password_confirmation" id="password_confirmation"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
               placeholder="••••••••" onfocus="this.placeholder=''" onblur="this.placeholder='••••••••'" required>
           </div>
 
           <div class="flex items-start">
             <div class="flex items-center h-5">
               <input id="newsletter" aria-describedby="newsletter" type="checkbox"
                 class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800" required>
             </div>
             <div class="mr-3 text-sm">
               <label for="newsletter" class="font-light text-gray-500 dark:text-gray-300">
                 أوافق على <a class="font-medium text-primary-600 hover:underline dark:text-primary-500" href="#">الشروط والأحكام</a>
               </label>
             </div>
           </div>
 
           <button type="submit"
             class="w-full border border-gray-300 text-gray-900 bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
             إعادة تعيين كلمة المرور
           </button>
         </form>
       </div>
     </div>
   </section>
 </x-base>
 