<x-layout dir="rtl">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-8 max-w-3xl mx-auto">
            <div class="text-center">
                <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                
                <h2 class="text-2xl font-bold text-gray-800 mb-4">تمت العملية بنجاح!</h2>
                <p class="text-lg text-gray-600 mb-6">{{ $message ?? 'تم إنشاء الفاتورة والحركة المخزنية بنجاح!' }}</p>
                
                <div class="mt-8">
                    <a href="{{ $redirect_url ?? '/invoices?type=sale' }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                        العودة إلى قائمة الفواتير
                    </a>
                </div>
                
                <div class="mt-4 text-gray-500">
                    <p>سيتم توجيهك تلقائياً خلال <span id="countdown">5</span> ثوان...</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // عد تنازلي وتوجيه تلقائي
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        const redirectUrl = "{{ $redirect_url ?? '/invoices?type=sale' }}";
        
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = redirectUrl;
            }
        }, 1000);
    </script>
</x-layout>
