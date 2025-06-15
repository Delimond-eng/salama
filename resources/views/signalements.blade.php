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
                    <a href="#">Signalements</a>
                </li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->
        <!-- BEGIN: Account Menu -->
        <x-user-session></x-user-session>
        <!-- END: Account Menu -->
    </div>
    <!-- END: Top Bar -->


    <div id="App" v-cloak>
        <div class="intro-y mt-8 flex flex-col items-center sm:flex-row">
            <h2 class="mr-auto text-lg font-medium">Les signalements</h2>

            <div class="mt-4 flex w-full sm:mt-0 sm:w-auto">
                <div class="relative w-56 text-slate-500 mr-2">
                    <input data-tw-merge="" v-model="filter_date" @input="viewAllSignalements" type="date" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56">
                </div>
                <div class="relative w-56 text-slate-500">
                    <select v-model="site_id" @change="viewAllSignalements" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 !box w-56">
                        <option value="" selected hidden>Voir par site</option>
                        <option v-for="(site, index) in sites" :value="site.id">@{{ site.name }}</option>
                    </select>
                </div>  
                <button @click="site_id=''; filter_date=''; viewAllSignalements()" class="bg-primary ml-2 text-white transition duration-200 border border-primary shadow-sm inline-flex items-center justify-center py-2 px-2 rounded-lg font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 disabled:opacity-70 disabled:cursor-not-allowed hover:bg-opacity-90 hover:border-opacity-90">
                    <span class="flex h-4 w-4 items-center justify-center">
                        <i class="w-3 h-3" data-lucide="rotate-ccw"></i>
                    </span>
                </button>
            </div>
        </div>
        <div class="intro-y mt-5 grid grid-cols-12 gap-6" v-if="allSignalements.length > 0">
            <!-- BEGIN: Blog Layout -->
            <div class="intro-y box col-span-12 md:col-span-6 xl:col-span-4" v-for="(data, index) in allSignalements" :key="index">
                <div class="flex items-center border-b border-slate-200/60 px-5 py-4 dark:border-darkmode-400">
                    <div class="image-fit h-10 w-10 flex-none" v-if="data.agent">
                        <img v-if="data.agent.photo" class="rounded-full" :src="data.agent.photo" alt="photo agent">
                        <img v-else class="rounded-full" src="{{ asset("assets/images/profil-2.png") }}" alt="photo agent">
                    </div>
                    <div class="ml-3 mr-auto">
                        <a class="font-medium" href="#" v-if="data.agent">
                            @{{ data.agent.fullname }}
                        </a>
                        <div class="mt-0.5 flex truncate text-xs text-slate-500">
                            <a class="inline-block truncate text-primary" href="#">
                                @{{ data.agent.matricule }}
                            </a>
                            <span class="mx-1">•</span> <span class="text-blue-500">@{{ data.formattedDate }}</span>
                        </div>
                    </div>
                    <div data-tw-merge="" data-tw-placement="bottom-end" class="relative ml-3">
                        <button @click="deleteSignt(data)" class="cursor-pointer h-5 w-5 text-slate-500">
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
                            <i v-else data-tw-merge="" data-lucide="trash-2" class="stroke-1.5 w-5 h-5 text-danger"></i>
                        </button>
                    </div>
                </div>
                <div class="p-5">
                    <!-- <div class="image-fit h-40 2xl:h-56" v-if="data.media">
                        <img data-action="zoom" class="rounded-md" src="dist/images/fakers/preview-12.jpg" alt="Midone - Tailwind Admin Dashboard Template">
                    </div> -->
                    <div v-if="data.media" class="image-fit h-40 2xl:h-56">
                        <!-- Si c'est une vidéo .mp4 -->
                        <video v-if="isVideo(data.media)" controls class="w-full h-40 2xl:h-56 rounded-md">
                            <source :src="data.media" type="video/mp4" />
                            Votre navigateur ne supporte pas la vidéo.
                        </video>

                        <!-- Sinon, c'est une image -->
                        <img v-else data-action="zoom" class="rounded-md h-40 2xl:h-56 w-full object-cover" :src="data.media" alt="Media">
                    </div>
                    <div v-else class="image-fit h-40 2xl:h-56">
                        <img class="rounded-md h-40 2xl:h-56 w-full object-cover" src="{{ asset('assets/images/bell.jpg') }}" alt="Media">
                    </div>
                    <a class="mt-5 block text-base font-medium" href="#">
                        @{{ data.title }}
                    </a>
                    <div class="mt-2 text-slate-600 dark:text-slate-500">
                         @{{ data.description }}.
                    </div>
                </div>
                <div class="border-t border-slate-200/60 px-5 pb-5 pt-3 dark:border-darkmode-400">
                    <div class="flex w-full text-xs text-slate-500 sm:text-sm">
                        <div class="mr-2">
                            <i class="w-4 h-4" data-lucide="home"></i>
                        </div>
                        <div class="">
                            
                        </div>
                        <div class="ml-auto text-blue-500 font-extrabold uppercase" v-if="data.site">
                            @{{ data.site.name }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Blog Layout -->
            <!-- BEGIN: Pagination -->
            <Pagination
                :current-page="pagination.current_page"
                :last-page="pagination.last_page"
                :total-items="pagination.total"
                :per-page="pagination.per_page"
                @page-changed="changePage"
                @per-page-changed="onPerPageChange"
            />
            <!-- END: Pagination -->
        </div>
        <div v-else>
            <div v-if="isDataLoading">
                <x-dom-loader></x-dom-loader>
            </div>
            <div v-else class="relative mt-5 intro-y before:box before:absolute before:inset-x-3 before:mt-3 before:h-full before:bg-slate-50 before:content-['']">
                <div class="box">
                    <x-empty-state message="Aucune signalement disponible."></x-empty-state>
                </div>
            </div>
        </div>
    </div>
    <x-dom-loader></x-dom-loader>
</div>
<!-- END: Content -->
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/requests.js") }}"></script>
@endpush