@props([
    "message"=> "Il n'y a aucun élèment répertorié pour l'instant !"
])
<div class="flex h-full items-center mt-14">
    <div class="mx-auto text-center">
        <div class="image-fit mx-auto h-16 w-16 flex-none">
            <img src="dist/images/search.svg" alt="Midone - Tailwind Admin Dashboard Template">
        </div>
        <div class="mt-3">
            <div class="font-extrabold text-lg">
                Aucun résultat disponible !
            </div>
            <div class="mt-1 text-slate-500">
                {{ $message }}
            </div>
        </div>
    </div>
</div>