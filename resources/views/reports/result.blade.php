<x-layout>
  
<div class="container">
    <h1>نتائج التقرير</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                @foreach($fields as $field)
                    <th>{{ $field }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    @foreach($fields as $field)
                        <td>{{ $result->$field }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <a href="{{ route('reports.create') }}" class="btn btn-secondary mt-3">إنشاء تقرير جديد</a>
</div>

</x-layout>