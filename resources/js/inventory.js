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
        return response.json();  // تأكد أن الاستجابة هي JSON
    })
    .then(data => {
        let effectValue = data.effect ?? '0';
        effectInput.value = effectValue;
        hiddenEffectInput.value = effectValue;
        effectInput.disabled = (effectValue === '+' || effectValue === '-');
    })
    .catch(error => {
        console.error('خطأ أثناء جلب التأثير:', error);
        effectInput.value = '0';
        hiddenEffectInput.value = '0';
        effectInput.disabled = false;
    });

    }
}

// استدعاء التحديث عند تغيير نوع العملية
document.getElementById('transaction_type_id').addEventListener('change', updateEffectValue);

// تحديث قيمة التأثير عند تحميل الصفحة
window.onload = function() {
    updateEffectValue();
};


// {{-- <SCRIPT>
//     const transactionTypeSelect = document.getElementById('transactionTypeSelect');
//     const selectedOption = transactionTypeSelect.options[transactionTypeSelect.selectedIndex];
//     const transactionTypeName = selectedOption ? selectedOption.value : null;
//     const hiddenEffectInput = document.getElementById('hidden-effect');

//     if (transactionTypeName) {
//         fetch(`/api/transaction-effect/${transactionTypeName}`)
//             .then(response => response.json())
//             .then(data => {
//                 const effectValue = data.effect || 0;

//                 // تعيين القيمة للعنصر المخفي
//                 const effectInput = document.getElementById('hiddenEffectInput');
//                 if (effectInput) {
//                     effectInput.value = effectValue;
//                 }
//             })
//             .catch(error => console.error('Error fetching data:', error));
//     } else {
//         console.log('Invalid transaction type selected');
//     }
// </SCRIPT> --}}
// {{-- <script>
//     // تحديث قيمة التأثير بناءً على نوع العملية
//     function updateEffectValue() {
//         const transactionTypeSelect = document.getElementById('transaction_type_id');
//         const effectInput = document.getElementById('effect');
//         const hiddenEffectInput = document.getElementById('hidden-effect');

//         const selectedOption = transactionTypeSelect.options[transactionTypeSelect.selectedIndex];
//         const effectValue = selectedOption ? selectedOption.getAttribute('data-effect') : '0';

//         effectInput.value = effectValue;
//         hiddenEffectInput.value = effectValue;

//         if (effectValue === '0') {
//             effectInput.disabled = false;
//         } else {
//             effectInput.disabled = true;
//         }
//     }

//     // استدعاء التحديث عند تغيير نوع العملية
//     document.getElementById('transaction_type_id').addEventListener('change', updateEffectValue);

//     // تحديث قيمة التأثير عند تحميل الصفحة
//     window.onload = function() {
//         updateEffectValue();
//     }
// </script>
// <script>
//     document.addEventListener("DOMContentLoaded", function() {
//         const transactionTypeSelect = document.getElementById("transaction_type_id");
//         const effectSelect = document.getElementById("effect");
//         const effectHidden = document.getElementById("effect_hidden");

//         function updateEffect() {
//             const selectedOption = transactionTypeSelect.options[transactionTypeSelect.selectedIndex];
//             const effectValue = selectedOption.dataset.effect;

//             // تحميل القيمة الافتراضية فقط إذا لم تكن قيمة التأثير 0
//             if (effectValue && effectValue !== "0") {
//                 effectSelect.value = effectValue;
//                 effectHidden.value = effectValue; // تحديث الحقل المخفي
//             }
//         }

//         // تعيين التأثير الافتراضي عند تحميل الصفحة إذا لم يكن 0
//         if (effectSelect.value === "0") {
//             updateEffect();
//         }

//         // تحديث التأثير عند تغيير نوع العملية
//         transactionTypeSelect.addEventListener("change", updateEffect);
//     });
// </script> --}}
