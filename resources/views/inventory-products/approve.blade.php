            <form method="POST" action="{{ route('transactions.approve') }}">
                @csrf
                <div class="form-group  flex items-center justify-between">
    
                <label for="method">اختيار مبدأ السحب:</label>
                <select name="method" id="method" class="tom-select w-30">
                    <option value="1">FEFO</option>
                    <option value="2">FIFO</option>
                </select>
                
                <label for="transaction_id">اختر رقم الحركة</label>
                <select name="transaction_id" id="transaction_id" class="form-control tom-select w-80" required>
                    <option value="">-- اختر رقم الحركة --</option>
                    @foreach ($transactions as $transaction)
                    <a href="{{ route('inventory.withdraw.print', ['id' => $transaction->id]) }}" target="_blank" class="btn btn-primary">
                        🖨️ طباعة مرة أخرى
                    </a>
                    
                        <option value="{{ $transaction->id }}">
                            رقم: {{ $transaction->id }} - التاريخ: {{ $transaction->created_at->format('Y-m-d') }}- الرقم المرحعي: {{ $transaction->reference }}
                        </option>
                       
                    @endforeach
                </select>
                
                    @error('transaction_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <x-button type="submit" class="btn btn-success mt-3 ml-4">اعتماد السحب</x-button>
                    
                </div>
    
            </form>
