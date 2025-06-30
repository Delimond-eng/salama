@extends('layouts.auth')

@section('content')
<div
    class="p-3 sm:px-8 relative h-screen lg:overflow-hidden xl:bg-white dark:bg-darkmode-800 xl:dark:bg-darkmode-600 before:hidden before:xl:block before:content-[''] before:w-[57%] before:-mt-[28%] before:-mb-[16%] before:-ml-[13%] before:absolute before:inset-y-0 before:left-0 before:transform before:rotate-[-4.5deg] before:bg-primary/20 before:rounded-[100%] before:dark:bg-darkmode-400 after:hidden after:xl:block after:content-[''] after:w-[57%] after:-mt-[20%] after:-mb-[13%] after:-ml-[13%] after:absolute after:inset-y-0 after:left-0 after:transform after:rotate-[-4.5deg] after:bg-primary after:rounded-[100%] after:dark:bg-darkmode-700">
    <div class="container relative z-10 sm:px-10">
        <div class="block grid-cols-2 gap-4 xl:grid">
            <!-- BEGIN: Login Info -->
            <div class="hidden min-h-screen flex-col xl:flex">
                <a class="-intro-x flex items-center pt-5" href="#">
                    <img class="w-6" src="dist/images/security.svg" alt="Salama">
                    <span class="ml-2 text-lg text-white font-bold"> Salama </span>
                </a>
                <div class="my-auto">
                    <img class="-intro-x -mt-16 w-1/2" src="assets/images/illustration.svg"
                        alt="Salama">
                    <div class="-intro-x mt-10 text-4xl font-medium leading-tight text-white">
                        Authentification
                        <br>
                        Centre de contrôle
                    </div>
                    <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">
                        Utilisez vos identifiants pour vous authentifier !
                    </div>
                </div>
            </div>
            <!-- END: Login Info -->
            <!-- BEGIN: Login Form -->
            <form id="App" v-cloak method="POST" @submit.prevent="login" action="{{ route('login') }}" class="my-10 flex h-screen py-5 xl:my-0 xl:h-auto xl:py-0 login-form">
                @csrf
                <div
                    class="mx-auto my-auto w-full rounded-md bg-white px-5 py-8 shadow-md dark:bg-darkmode-600 sm:w-3/4 sm:px-8 lg:w-2/4 xl:ml-20 xl:w-auto xl:bg-transparent xl:p-0 xl:shadow-none">
                    <h2 class="intro-x text-center text-2xl font-bold xl:text-left xl:text-3xl">
                        Login
                    </h2>
                    <div class="intro-x mt-2 text-sm text-center text-slate-400 xl:hidden">
                        Utilisez vos identifiants pour vous authentifier !
                    </div>
                    <div class="intro-x mt-8">
                        <input data-tw-merge="" type="email" placeholder="Email" name="email"
                            class="input-form disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 intro-x block min-w-full px-4 py-3 xl:min-w-[350px]">
                        <input data-tw-merge="" type="password" placeholder="Mot de passe" name="password"
                            class="input-form disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 intro-x mt-4 block min-w-full px-4 py-3 xl:min-w-[350px]">
                    </div>
                    <div class="intro-x mt-5 text-center xl:mt-8 xl:text-left">
                        <button :disabled="isLoading" type="submit" data-tw-merge=""
                            class="transition duration-200 border shadow-sm inline-flex items-center justify-center rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary w-full px-4 py-3 align-top xl:mr-3 xl:w-full">
                            Connecter   <span class="ml-2 h-4 w-4" v-if="isLoading">
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
                    <div class="intro-x mt-10 flex items-center justify-center text-slate-600 dark:text-slate-500 xl:mt-24 xl:text-left">
                        Salama Plateforme. Tous droits réservés
                    </div>
                </div>
            </form>
            <div class="flex h-screen items-center" id="loader">
                <div class="mx-auto text-center">
                    <div>
                        <img src="{{ asset('assets/images/loading.gif') }}" class="w-12 h-12" />
                    </div>
                </div>
            </div>
            <!-- END: Login Form -->
            <div id="failed-notification-content" class="py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 hidden flex">
                <i data-tw-merge="" data-lucide="x-circle" class="stroke-1.5 w-5 h-5 text-danger"></i>
                <div class="ml-4 mr-4">
                    <div class="font-medium">Echec d'authentification !</div>
                    <div class="text-slate-500 mt-1">Email ou mot de passe incorrect. </div>
                </div>
            </div>
            <div id="success-notification-content" class="py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 hidden flex">
                <i data-tw-merge="" data-lucide="check-circle" class="stroke-1.5 w-5 h-5 text-success"></i>
                <div class="ml-4 mr-4">
                    <div class="font-medium">Connexion reussi !</div>
                    <div class="text-slate-500 mt-1">Redirection vers la page d'administration! </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push("scripts")
<script type="module" src="{{ asset("assets/js/scripts/auth.js") }}"></script>
@endpush