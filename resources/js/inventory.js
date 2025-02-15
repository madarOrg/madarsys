
/////////units
document.addEventListener('change', function(event) {
    if (event.target.classList.contains('product-select')) {
        const productId = event.target.value;
        const row = event.target.closest('.product-row');
        if (!row) return;
        const unitsSelect = row.querySelector('.units-select');
        if (!unitsSelect) return;

        // إعادة تعيين قائمة الوحدات
        unitsSelect.innerHTML = '<option value="">اختر وحدة</option>';

        if (productId) {
            fetch(`/get-units/${productId}`)
                .then(response => response.json())
                .then(data => {
                    data.units.forEach(unit => {
                        const option = document.createElement('option');
                        option.value = unit.id;
                        option.textContent = unit.name;
                        unitsSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error("خطأ في جلب الوحدات:", error);
                });
        }
    }
});

/////effect/////////////////////////////////////////////
function updateEffectValue() {
    const transactionTypeSelect = document.getElementById('transaction_type_id');
    const effectInput = document.getElementById('effect');
    const hiddenEffectInput = document.getElementById('hidden-effect');

    // الحصول على القيمة المختارة
    const selectedOption = transactionTypeSelect.options[transactionTypeSelect.selectedIndex];
    const transactionTypeId = selectedOption ? selectedOption.value : null;

    if (transactionTypeId) {
        // إرسال طلب لجلب التأثير من الخادم
        fetch(`/transaction-effect/${transactionTypeId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('استجابة غير صالحة من الخادم');
                }
                return response.json(); // تأكد أن الاستجابة بصيغة JSON
            })
            .then(data => {
                let effectValue = data.effect ?? '0';
                
                // ضبط القيم في كل من الحقل الظاهر والمخفي
                effectInput.value = effectValue;
                hiddenEffectInput.value = effectValue;

                // اجعل الحقل غير متاح إذا كانت القيمة 0، وإلا اجعله متاحًا
                effectInput.disabled = (effectValue != '0');
            })
            .catch(error => {
                console.error('خطأ أثناء جلب التأثير:', error);
                
                // عند الخطأ، اجعل القيمة 0
                effectInput.value = '0';
                hiddenEffectInput.value = '0';
                effectInput.disabled = true; // اجعل الحقل غير متاح
            });
    }
}

// استدعاء التحديث عند تغيير نوع العملية
document.getElementById('transaction_type_id').addEventListener('change', updateEffectValue);

// تحديث قيمة التأثير عند تحميل الصفحة
window.onload = function() {
    updateEffectValue();
};

