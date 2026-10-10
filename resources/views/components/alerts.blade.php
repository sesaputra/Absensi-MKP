{{-- =========================================================
    SUCCESS NOTIFICATION
========================================================== --}}
@if(session('success'))
<div
    id="successAlert"
    class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
    <div class="flex-1">
        <p class="text-[12px] font-semibold">Berhasil</p>
        <p class="mt-0.5 text-[11px] leading-5">{{ session('success') }}</p>
    </div>
    <button type="button" onclick="document.getElementById('successAlert')?.remove()" class="text-emerald-600 transition hover:text-emerald-800">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
@endif


{{-- =========================================================
    VALIDATION ERROR
========================================================== --}}
@if($errors->any())
<div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
    <div class="flex items-start gap-3">
        <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 4h.01M10.29 3.86l-8.82 15A2 2 0 003.2 22h17.6a2 2 0 001.73-3.14l-8.82-15a2 2 0 00-3.42 0z" />
        </svg>
        <div>
            <p class="text-[12px] font-semibold">Terjadi kesalahan</p>
            <ul class="mt-1 list-disc pl-4 text-[11px] leading-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif