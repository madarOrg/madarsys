
/////////units////////////////////////////////////////////////////////////
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
document.addEventListener('change', function(event) {
    if (event.target.classList.contains('product-select')) {
        const productId = event.target.value;
        const row = event.target.closest('.product-row');
        if (!row) return;
        const unitsSelect = row.querySelector('.units-select');
        if (!unitsSelect) return;

        // إعادة تعيين قائمة الوحدات
        unitsSelect.innerHTML = '';

        if (productId) {
            fetch(`/get-units/${productId}`)
                .then(response => response.json())
                .then(data => {
                    const defaultUnitId = data.default_unit_id; // وحدة المنتج الافتراضية
                    const units = data.units;

                    // إضافة الوحدات إلى القائمة
                    units.forEach(unit => {
                        const option = document.createElement('option');
                        option.value = unit.id;
                        option.textContent = unit.name;

                        // اجعل وحدة المنتج الافتراضية هي المحددة
                        if (unit.id === defaultUnitId) {
                            option.selected = true;
                        }

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
