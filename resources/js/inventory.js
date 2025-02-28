
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
   //   تعديل ظهور المستودع الثانوي عند التخحويل المخزني
   document.addEventListener("DOMContentLoaded", function () { //تحديد العناصر المطلوبة
    const transactionTypeSelect = document.getElementById("transaction_type_id");
    const secondaryWarehouseContainer = document.getElementById("secondary_warehouse_container"); 
// تعريف الدالة المسؤولة عن إظهار أو إخفاء المستودع الثانوي
    function toggleSecondaryWarehouse() {
        const selectedTransaction = transactionTypeSelect.options[transactionTypeSelect.selectedIndex];
        const isStockTransfer = selectedTransaction.text.includes("تحويل مخزني"); 
        secondaryWarehouseContainer.style.display = isStockTransfer ? "block" : "none";
    }

    transactionTypeSelect.addEventListener("change", toggleSecondaryWarehouse);
    toggleSecondaryWarehouse(); //  استدعاء الدالة عند تحميل الصفحة للتحقق من الاختيار الافتراضي لضبط حالة العرض بناءً على القيمة الافتراضية في القائمة
});
