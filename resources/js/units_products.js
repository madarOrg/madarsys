
// /////////units////////////////////////////////////////////////////////////
//     console.log("Livewire is ready, JS is running...");

// document.addEventListener('change', function(event) {
//     if (event.target.classList.contains('product-select')) {
//         const productId = event.target.value;
//         const row = event.target.closest('.product-row');
//         if (!row) return;
//         const unitsSelect = row.querySelector('.units-select');
//         if (!unitsSelect) return;

//         // إعادة تعيين قائمة الوحدات
//         unitsSelect.innerHTML = '<option value="">اختر وحدة</option>';

//         if (productId) {
//             fetch(`/get-units/${productId}`)
//                 .then(response => response.json())
//                 .then(data => {
//                     data.units.forEach(unit => {
//                         const option = document.createElement('option');
//                         option.value = unit.id;
//                         option.textContent = unit.name;
//                         unitsSelect.appendChild(option);
//                     });
//                 })
//                 .catch(error => {
//                     console.error("خطأ في جلب الوحدات:", error);
//                 });
//         }
//     }
// });
// document.addEventListener('change', function(event) {
//     if (event.target.classList.contains('product-select')) {
//         const productId = event.target.value;
//         const row = event.target.closest('.product-row');
//         if (!row) return;
//         const unitsSelect = row.querySelector('.units-select');
//         if (!unitsSelect) return;

//         // إعادة تعيين قائمة الوحدات
//         unitsSelect.innerHTML = '';

//         if (productId) {
//             fetch(`/get-units/${productId}`)
//                 .then(response => response.json())
//                 .then(data => {
//                     const defaultUnitId = data.default_unit_id; // وحدة المنتج الافتراضية
//                     const units = data.units;

//                     // إضافة الوحدات إلى القائمة
//                     units.forEach(unit => {
//                         const option = document.createElement('option');
//                         option.value = unit.id;
//                         option.textContent = unit.name;

//                         // اجعل وحدة المنتج الافتراضية هي المحددة
//                         if (unit.id === defaultUnitId) {
//                             option.selected = true;
//                         }

//                         unitsSelect.appendChild(option);
//                     });
//                 })
//                 .catch(error => {
//                     console.error("خطأ في جلب الوحدات:", error);
//                 });
//         }
//     }
// })
document.addEventListener('change', function (event) {
    if (event.target.classList.contains('product-select')) {
        const productId = event.target.value;
        const row = event.target.closest('.product-row');
        if (!row) return;
        populateUnits(row, productId);
    }
});

// دالة لتعبئة قائمة الوحدات بناءً على معرف المنتج
function populateUnits(row, productId) {
    console.log("Livewire is ready, JS is running...");

    const unitsSelect = row.querySelector('.units-select');
    if (!unitsSelect) return;
    
    // إعادة تعيين قائمة الوحدات مع خيار افتراضي
    unitsSelect.innerHTML = '<option value="">اختر وحدة</option>';
    
    if (productId) {
        fetch(`/get-units/${productId}`)
            .then(response => response.json())
            .then(data => {
                const defaultUnitId = data.default_unit_id; // وحدة المنتج الافتراضية
                data.units.forEach(unit => {
                    const option = document.createElement('option');
                    option.value = unit.id;
                    option.textContent = unit.name;
                    // إذا كانت الوحدة هي الافتراضية، نجعلها المختارة
                    if (unit.id == defaultUnitId) {
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

// عند تحميل الصفحة، تعبئة الوحدات في كل صف يحتوي على منتج محدد
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.product-row').forEach(row => {
        const productSelect = row.querySelector('.product-select');
        if (productSelect && productSelect.value) {
            populateUnits(row, productSelect.value);
        }
    });
});

// عند تغيير اختيار المنتج، تعبئة قائمة الوحدات تبعاً لذلك
document.addEventListener('change', function(event) {
    if (event.target.classList.contains('product-select')) {
        const productId = event.target.value;
        const row = event.target.closest('.product-row');
        if (!row) return;
        populateUnits(row, productId);
    }
});

