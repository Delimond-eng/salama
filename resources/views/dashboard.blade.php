@extends("layouts.app")

@section("content")
<!-- BEGIN: Content -->
<div
    class="md:max-w-auto min-h-screen min-w-0 max-w-full flex-1 rounded-[30px] bg-slate-100 px-4 pb-10 before:block before:h-px before:w-full before:content-[''] dark:bg-darkmode-700 md:px-[22px]">
    <!-- BEGIN: Top Bar -->
    <div class="relative z-[51] flex h-[67px] items-center border-b border-slate-200">
        <!-- BEGIN: Breadcrumb -->
        <nav aria-label="breadcrumb" class="flex -intro-x mr-auto hidden sm:flex">
            <ol class="flex items-center text-theme-1 dark:text-slate-300">
                <li class="">
                    <a href="#">Salama</a>
                </li>
                <li
                    class="relative ml-5 pl-0.5 before:content-[''] before:w-[14px] before:h-[14px] before:bg-chevron-black before:transform before:rotate-[-90deg] before:bg-[length:100%] before:-ml-[1.125rem] before:absolute before:my-auto before:inset-y-0 dark:before:bg-chevron-white text-slate-800 cursor-text dark:text-slate-400">
                    <a href="#">Tableau de bord</a>
                </li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->
        <!-- BEGIN: Account Menu -->
        <div data-tw-merge="" data-tw-placement="bottom-end" class="dropdown relative">
            <button
                data-tw-toggle="dropdown" aria-expanded="false"
                class="cursor-pointer zoom-in intro-x block h-9 w-9 bg-success text-white overflow-hidden rounded-full shadow-lg">
                <h1 style="font-weight: 900;">{{ substr(Auth::user()->name, 0, 1) }}</h1>
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
                    class="dropdown-content rounded-md border-transparent p-2 shadow-[0px_3px_10px_#00000017] dark:border-transparent dark:bg-darkmode-600 mt-px w-56 bg-theme-1 text-white">
                    <div class="p-2 font-medium font-normal">
                        <div class="font-medium">{{ Auth::user()->name }}</div>
                        <div class="mt-0.5 text-xs text-white/70 dark:text-slate-500">
                            {{ Auth::user()->email }}
                        </div>
                    </div>
                    <div class="h-px my-2 -mx-2 bg-slate-200/60 dark:bg-darkmode-400 bg-white/[0.08]">
                    </div>
                    <a
                        class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item hover:bg-white/5"><i
                            data-tw-merge="" data-lucide="user" class="stroke-1.5 mr-2 h-4 w-4"></i>
                        Profile</a>
                    <a
                        class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item hover:bg-white/5"><i
                            data-tw-merge="" data-lucide="edit" class="stroke-1.5 mr-2 h-4 w-4"></i>
                        Add Account</a>
                    <a
                        class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item hover:bg-white/5"><i
                            data-tw-merge="" data-lucide="lock" class="stroke-1.5 mr-2 h-4 w-4"></i>
                        Reset Password</a>
                    <a
                        class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item hover:bg-white/5"><i
                            data-tw-merge="" data-lucide="help-circle" class="stroke-1.5 mr-2 h-4 w-4"></i>
                        Help</a>
                    <div class="h-px my-2 -mx-2 bg-slate-200/60 dark:bg-darkmode-400 bg-white/[0.08]">
                    </div>
                    <form id="logout-form" hidden action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item hover:bg-white/5"><i
                            data-tw-merge="" data-lucide="toggle-right" class="stroke-1.5 mr-2 h-4 w-4"></i>
                        Logout</a>

                </div>
            </div>
        </div>
        <!-- END: Account Menu -->
    </div>
    <!-- END: Top Bar -->
    <div class="grid grid-cols-12 gap-6" id="App">
        <div class="col-span-12" :class="allPendingPatrols.length > 0 ? '2xl:col-span-9' : '2xl:col-span-12'">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Official Store -->
                <div class="col-span-12 mt-6 xl:col-span-12">
                    <div class="intro-y block h-10 items-center sm:flex">
                        <h2 class="mr-5 truncate text-lg font-medium">Tableau de bord</h2>
                        <div class="relative mt-3 text-slate-500 sm:ml-auto sm:mt-0">
                            <i data-tw-merge="" data-lucide="map-pin"
                                class="stroke-1.5 absolute inset-y-0 left-0 z-10 my-auto ml-3 h-4 w-4"></i>
                            <input data-tw-merge="" type="text" placeholder="Filtrer par site..."
                                class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box pl-10 sm:w-56">
                        </div>
                    </div>
                    <div class="intro-y box mt-12 p-5 sm:mt-5">
                        <div>
                            La cartographie de tous les sites qui utilisent la plateforme salama.
                        </div>
                        <div data-lat="-6.2425342" data-long="106.8626478" data-sources=""
                            class="leaflet z-0 [&_.leaflet-tile-pane]:contrast-105 [&_.leaflet-tile-pane]:grayscale [&_.leaflet-tile-pane]:dark:contrast-[.8] [&_.leaflet-tile-pane]:dark:invert mt-5 rounded-md bg-slate-200" style="height: 500px;">
                        </div>
                    </div>
                </div>
                <!-- END: Official Store -->
            </div>
        </div>
        <div class="col-span-12 2xl:col-span-3" v-if="allPendingPatrols.length > 0">
            <div class="-mb-10 pb-10 2xl:border-l">
                <div class="grid grid-cols-12 gap-x-6 gap-y-6 2xl:gap-x-0 2xl:pl-6">
                    <!-- BEGIN: Transactions -->
                    <div class="col-span-12 mt-3 md:col-span-6 xl:col-span-4 2xl:col-span-12 2xl:mt-8">
                        <div class="intro-x flex h-10 items-center">
                            <h2 class="mr-5 truncate text-lg font-medium">Patrouilles en cours</h2>
                        </div>
                        <div class="mt-5">
                            <div class="intro-x" v-for="i in 4" :key="i">
                                <div class="box zoom-in mb-3 flex items-center px-5 py-3">
                                    <div class="image-fit h-10 w-10 flex-none overflow-hidden rounded-full">
                                        <img src="dist/images/fakers/profile-4.jpg"
                                            alt="Midone - Tailwind Admin Dashboard Template">
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium">Denzel Washington</div>
                                        <div class="mt-0.5 text-xs text-slate-500">
                                            1 September 2021
                                        </div>
                                    </div>
                                    <div class="text-success">
                                        +$33
                                    </div>
                                </div>
                            </div>
                            <a class="intro-x block w-full rounded-md border border-dotted border-slate-400 py-3 text-center text-slate-500 dark:border-darkmode-300"
                                href="#">
                                Vue large
                            </a>
                        </div>
                    </div>
                    <!-- END: Transactions -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/monitoring.js") }}"></script>
@endpush
