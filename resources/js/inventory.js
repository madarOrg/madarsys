
/////effect/////////////////////////////////////////////
function updateEffectValue() {
    const transactionTypeSelect = document.getElementById('transaction_type_id');
    const effectSelect = document.getElementById('effect');
    const hiddenEffectInput = document.getElementById('hidden-effect');

    const selectedOption = transactionTypeSelect.options[transactionTypeSelect.selectedIndex];
    const effectValue = selectedOption.getAttribute('data-effect');

    if (effectValue !== "0") {
        // إذا لم تكن القيمة 0، يتم التحديث تلقائيًا
        effectSelect.value = effectValue;
        hiddenEffectInput.value = effectValue;
        effectSelect.disabled = true; // تعطيل التعديل اليدوي
    } else {
        // إذا كانت القيمة 0، يتم تمكين التعديل اليدوي
        effectSelect.disabled = false;
    }
}

// تحديث القيمة المخفية عند تغيير الاختيار اليدوي
document.getElementById('effect').addEventListener('change', function() {
    document.getElementById('hidden-effect').value = this.value;
});

document.getElementById('transaction_type_id').addEventListener('change', updateEffectValue);

// استدعاء الوظيفة عند تحميل الصفحة لضبط القيم المبدئية
updateEffectValue();


////////////////////////////////////////////////////////////////////////
   //   تعديل ظهور المستودع الثانوي عند التحويل المخزني
document.addEventListener("DOMContentLoaded", function () {
    // تحديد العناصر المطلوبة
    const transactionTypeSelect = document.getElementById("transaction_type_id");
    const secondaryWarehouseContainer = document.getElementById("secondary_warehouse_container");

    // تعريف الدالة المسؤولة عن إظهار أو إخفاء المستودع الثانوي
    function toggleSecondaryWarehouse() {
        const selectedValue = transactionTypeSelect.value;
        secondaryWarehouseContainer.style.display = selectedValue === "5" ? "block" : "none";
    }

    // ربط الحدث بالتغيير واستدعاء الدالة عند التحميل
    transactionTypeSelect.addEventListener("change", toggleSecondaryWarehouse);
    toggleSecondaryWarehouse(); // للتحقق من القيمة الافتراضية عند تحميل الصفحة
});
