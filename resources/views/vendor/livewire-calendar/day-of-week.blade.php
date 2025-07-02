<div class="flex-1 h-12 border -mt-px -ml-px flex bg-neutral items-center justify-center text-neutral-content  {{ ($day->dayOfWeek == 0) ? 'rounded-tr-xl' : (($day->dayOfWeek == 1) ? 'rounded-tl-xl' : '') }}"
     style="min-width: 10rem;">

    <p class="text-sm">
        {{ Str::of($day->locale('pt_BR')->translatedFormat('l'))->replace('-feira', '')->title() }}
    </p>

</div>
