@extends("layouts.app")


@section("content")
<!-- BEGIN: Content -->
<div class="md:max-w-auto min-h-screen min-w-0 max-w-full flex-1 rounded-[30px] bg-slate-100 px-4 pb-10 before:block before:h-px before:w-full before:content-[''] dark:bg-darkmode-700 md:px-[22px]">
    <!-- BEGIN: Top Bar -->
    <div class="relative z-[51] flex h-[67px] items-center border-b border-slate-200">
        <!-- BEGIN: Breadcrumb -->
        <nav aria-label="breadcrumb" class="flex -intro-x mr-auto hidden sm:flex">
            <ol class="flex items-center text-theme-1 dark:text-slate-300">
                <li class="">
                    <a href="#">Salama</a>
                </li>
                <li class="relative ml-5 pl-0.5 before:content-[''] before:w-[14px] before:h-[14px] before:bg-chevron-black before:transform before:rotate-[-90deg] before:bg-[length:100%] before:-ml-[1.125rem] before:absolute before:my-auto before:inset-y-0 dark:before:bg-chevron-white text-slate-800 cursor-text dark:text-slate-400">
                    <a href="#">Liste des sites</a>
                </li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->

        <!-- BEGIN: Account Menu -->
        <div data-tw-merge="" data-tw-placement="bottom-end" class="dropdown relative"><button
                data-tw-toggle="dropdown" aria-expanded="false"
                class="cursor-pointer zoom-in intro-x block h-9 w-9 bg-primary text-white overflow-hidden rounded-full shadow-lg">
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
                            data-tw-merge="" data-lucide="help-circle" class="stroke-1.5 mr-2 h-4 w-4"></i>
                        Aide</a>
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
    <div class="mt-5 grid grid-cols-12 gap-6" id="App" v-cloak>
        <div class="col-span-12 lg:col-span-8">
            <div class="relative mt-5 intro-y before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                <!-- BEGIN: Vertical Form -->
                <div class="intro-x box">
                    <div class="flex flex-wrap items-center border-b border-slate-200/60 px-5 py-5 dark:border-darkmode-400 sm:py-3">
                        <h2 class="mr-auto text-base font-bold uppercase">Liste des sites</h2>
                        <div class="mt-3 flex w-full items-center xl:mt-0 xl:w-auto">
                            <div class="relative w-56 text-slate-500 mr-2">
                                <input data-tw-merge="" v-model="search" type="text" placeholder="Recherche..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56 pr-10">
                                <i data-tw-merge="" data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                            </div>
                            <button onclick="location.href='/site.create'" class="bg-primary text-white transition duration-200 border border-primary shadow-sm inline-flex items-center justify-center py-2 px-2 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 disabled:opacity-70 disabled:cursor-not-allowed hover:bg-opacity-90 hover:border-opacity-90">
                                <span class="flex h-5 w-5 items-center justify-center">
                                    <i class="w-4 h-4" data-lucide="plus"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="space-y-4" v-if="allSites.length > 0">
                            <div v-for="(data, i) in allSites" :key="i" class="border-b border-dashed border-slate-400  overflow-hidden">
                                <!-- Header -->
                                <div
                                    class="flex items-center justify-between px-6 py-5 hover:bg-slate-50 transition-colors duration-200 cursor-pointer">
                                    <!-- Site Infos -->
                                    <div class="flex items-center gap-4">
                                        <i data-lucide="home" class="h-6 w-6 text-primary"></i>
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-800">@{{ data.name }}</h3>
                                            <p class="text-sm text-slate-500">CODE : @{{ data.code }} | SECTEUR : @{{ data.secteur.libelle }}</p>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2">
                                        <button
                                            @click.stop="toggleAccordion(i)"
                                            class="bg-primary/10 text-primary border border-primary/30 rounded-lg px-2 py-2 text-sm hover:bg-primary/20 hover:border-primary/50">
                                            <i v-if="openAccordion === i" data-lucide="eye-off" class="w-4 h-4"></i>
                                            <i v-else data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                        <button data-tw-toggle="modal" data-tw-target="#header-footer-modal-preview" @click="selectedAreas = data.areas; form.id = data.id; form.name = data.name; error=null" class="bg-blue-100 text-blue-600 border border-blue-300 rounded-lg px-2 py-2 text-sm hover:bg-blue-200 hover:border-blue-400">
                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                        </button>
                                        <button @click="deleteSite(data)" class="bg-red-100 text-danger border border-red-300 rounded-lg px-2 py-2 text-sm hover:bg-red-200 hover:border-red-400">
                                            <span class="h-3 w-3" v-if="data.id === delete_id">
                                                <svg width="14" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="red">
                                                    <g fill="none" fill-rule="evenodd">
                                                        <g transform="translate(1 1)" stroke-width="4">
                                                            <circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle>
                                                            <path d="M36 18c0-9.94-8.06-18-18-18">
                                                                <animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform>
                                                            </path>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </span>
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Content -->
                                <transition name="fade">
                                    <div v-if="openAccordion === i" class="bg-slate-50 border-t border-slate-200 px-6 py-5">
                                        <!-- Areas List -->
                                        <div class="flex flex-wrap gap-3 mb-4">
                                            <div
                                                v-for="(area, j) in data.areas"
                                                :key="j"
                                                class="flex items-center bg-white text-sm border border-slate-300 px-3 py-1.5 rounded-full shadow-sm">
                                                <i data-lucide="map-pin" class="w-3 h-3 mr-1 text-primary"></i>
                                                <span>@{{ area.libelle }}</span>
                                                <button @click.stop="deleteArea(area.id)" class="ml-2 text-danger hover:text-red-600">
                                                    <span v-if="load_id === area.id" class="h-3 w-3">
                                                        <svg class="h-full w-full" width="15" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg">
                                                            <defs>
                                                                <linearGradient id="a" x1="8.042%" y1="0%" x2="65.682%" y2="23.865%">
                                                                    <stop stop-color="#2d3748" stop-opacity="0" offset="0%" />
                                                                    <stop stop-color="#2d3748" offset="100%" />
                                                                    <stop stop-color="#2d3748" stop-opacity=".631" offset="63.146%" />
                                                                </linearGradient>
                                                            </defs>
                                                            <g fill="none" fill-rule="evenodd">
                                                                <g transform="translate(1 1)">
                                                                    <path id="Oval-2" d="M36 18c0-9.94-8.06-18-18-18" stroke="url(#a)" stroke-width="3">
                                                                        <animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite" />
                                                                    </path>
                                                                    <circle fill="#2d3748" cx="36" cy="18" r="1">
                                                                        <animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite" />
                                                                    </circle>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                    <i v-else class="h-3 w-3" data-lucide="x"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Hint -->
                                        <p class="text-xs text-slate-500 mb-4">
                                            Veuillez cliquer en bas pour imprimer la liste des QR codes pour chaque zone à patrouiller.
                                        </p>

                                        <!-- Download Button -->
                                        <button @click.stop="downloadQRCode(data.id)"
                                            class="group relative inline-flex items-center gap-2 px-5 py-2 rounded-full bg-primary text-white font-medium hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/50">
                                            <span>Télécharger qrcodes</span>
                                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-primary group-hover:bg-slate-100">
                                                <i class="stroke-1.5 h-4 w-4" data-lucide="arrow-right"></i>
                                            </span>
                                        </button>
                                    </div>
                                </transition>
                            </div>
                        </div>
                        <div class="space-y-4" v-else>
                            <div v-if="isDataLoading">
                                <x-dom-loader></x-dom-loader>
                            </div>
                            <div v-else>
                                <x-empty-state message="Aucune requête disponible pour l'instant." v-else></x-empty-state>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Vertical Form -->
            </div>
        </div>

        <!-- BEGIN: Modal Content -->
        <div
            data-tw-backdrop=""
            aria-hidden="true"
            tabindex="-1"
            id="header-footer-modal-preview" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]">
            <form @submit.prevent="createSite" method="POST"
                data-tw-merge id="form-site"
                class="form-site w-[90%] mx-auto bg-white relative rounded-md shadow-md transition-[margin-top,transform] duration-[0.4s,0.3s] -mt-16 group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] dark:bg-darkmode-600 sm:w-[600px]">
                <div
                    class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="mr-auto text-base font-medium" v-if="form.name">
                        Ajout zones de patrouille | site : @{{ form.name }}
                    </h2>
                    <button id="btn-reset"
                        data-tw-merge
                        data-tw-dismiss="modal"
                        class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 hidden sm:flex hidden sm:flex"><i
                            data-tw-merge
                            data-lucide="x"
                            class="stroke-1.5 h-4 w-4"></i>
                    </button>
                </div>
                <div
                    data-tw-merge v-if="form.areas"
                    class="p-5 grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <div v-if="error" role="alert" class="alert relative border rounded-md px-5 py-4 border-pending text-pending dark:border-pending mb-2 flex items-center"><i data-tw-merge data-lucide="alert-circle" class="stroke-1.5 w-5 h-5 mr-2 h-6 w-6 mr-2 h-6 w-6"></i>
                            Erreur survenue lors du traitement de la requête.@{{ error }}
                            <button data-tw-merge data-tw-dismiss="alert" type="button" aria-label="Close" type="button" aria-label="Close" class="text-slate-800 py-2 px-3 absolute right-0 my-auto mr-2 btn-close"><i data-tw-merge data-lucide="x" class="stroke-1.5 w-5 h-5 h-4 w-4 h-4 w-4"></i></button>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-12" v-for="(input, j) in form.areas">
                        <label class="block mb-2 text-sm font-medium">
                            Libellé zone @{{ j + 1 }}
                        </label>

                        <div class="flex gap-2">
                            <input
                                type="text"
                                v-model="input.libelle"
                                placeholder="Nom de la zone" required
                                class="input-form border-danger-subtle flex-1 disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80" />

                            <button
                                v-if="j === 0"
                                @click.prevent="form.areas.push({ libelle: '' })"
                                type="button"
                                class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 dark:bg-darkmode-700 dark:text-slate-300 dark:border-darkmode-400 dark:hover:bg-darkmode-600">
                                <i data-lucide="plus" class="w-3 h-3"></i>
                            </button>

                            <button
                                v-else
                                @click.prevent="form.areas.splice(j, 1)"
                                type="button"
                                class="inline-flex items-center text-danger justify-center px-3 py-2 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-md shadow-sm hover:bg-red-50 dark:bg-darkmode-700 dark:border-darkmode-400 dark:hover:bg-darkmode-600">
                                <i data-lucide="x" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </div>

                </div>
                <div
                    class="px-5 py-3 text-right border-t border-slate-200/60 dark:border-darkmode-400"><button
                        data-tw-merge
                        data-tw-dismiss="modal" type="button" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 mr-1 w-20 mr-1 w-20">Fermer</button>
                    <button
                        data-tw-merge
                        type="submit" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary w-auto" :disabled="isLoading">Soumettre les ajouts <span class="ml-2 h-4 w-4" v-if="isLoading">
                            <svg class="h-full w-full" width="25" viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke="white">
                                <g fill="none" fill-rule="evenodd">
                                    <g transform="translate(1 1)" stroke-width="4">
                                        <circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle>
                                        <path d="M36 18c0-9.94-8.06-18-18-18">
                                            <animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform>
                                        </path>
                                    </g>
                                </g>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
        <!-- END: Modal Content -->


        <!-- toast error & success -->
        <div id="failed-notification-content" class="py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 hidden flex">
            <i data-tw-merge="" data-lucide="x-circle" class="stroke-1.5 w-5 h-5 text-danger"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Echec de traitement !</div>
                <div class="text-slate-500 mt-1">Erreur survenue lors du traitement de la requête.</div>
            </div>
        </div>
        <div id="success-notification-content" class="py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 hidden flex">
            <i data-tw-merge="" data-lucide="check-circle" class="stroke-1.5 w-5 h-5 text-success"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Opération reussi !</div>
                <div class="text-slate-500 mt-1">L'ajout des zones au site effectué ! </div>
            </div>
        </div>
        <!-- end toast -->
    </div>

</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/areas_manager.js") }}"></script>
@endpush

@push("styles")
<style scoped>
    .fade-enter-active,
    .fade-leave-active {
        transition: all 0.2s ease;
    }

    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
        transform: translateY(-5px);
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: all 0.3s ease;
    }

    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
        transform: translateY(-5px);
    }
</style>
@endpush