<div data-tw-merge="" data-tw-placement="bottom-end" class="dropdown relative"><button
        data-tw-toggle="dropdown" aria-expanded="false"
        class="cursor-pointer zoom-in intro-x block h-10 w-10 border-dashed border-slate-200 bg-dark text-white overflow-hidden rounded-full shadow-lg">
        <h1 style="font-weight: 900;" class="text-body">{{ substr(Auth::user()->name, 0, 1) }}</h1>
    </button>
    <div data-transition="" data-selector=".show"
        data-enter="transition-all ease-linear duration-150"
        data-enter-from="absolute !mt-5 invisible opacity-0 translate-y-1"
        data-enter-to="!mt-1 visible opacity-100 translate-y-0"
        data-leave="transition-all ease-linear duration-150"
        data-leave-from="!mt-1 visible opacity-100 translate-y-0"
        data-leave-to="absolute !mt-5 invisible opacity-0 translate-y-1"
        class="dropdown-menu absolute z-[9999] hidden">
        <div data-tw-merge=""
            class="dropdown-content rounded-lg border-transparent p-2 shadow-[0px_3px_10px_#00000017] dark:border-transparent dark:bg-darkmode-600 mt-px w-56 bg-dark text-white">
            <div class="p-2 font-medium font-normal">
                <div class="font-medium">{{ Auth::user()->name }}</div>
                <div class="mt-0.5 text-xs text-white/70 dark:text-slate-500">
                    {{ Auth::user()->email }}
                </div>
            </div>
            <div class="h-px my-2 -mx-2 bg-slate-200/60 dark:bg-darkmode-400 bg-white/[0.08]">
            </div>
            <!-- <div class="h-px my-2 -mx-2 bg-slate-200/60 dark:bg-darkmode-400 bg-white/[0.08]">
            </div> -->
            <form id="logout-form" hidden action="{{ route('logout') }}" method="POST">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item hover:bg-white/5"><i
                    data-tw-merge="" data-lucide="toggle-right" class="stroke-1.5 mr-2 h-4 w-4"></i>
                Déconnexion</a>
        </div>
    </div>
</div>