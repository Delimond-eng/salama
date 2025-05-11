push@extends("layouts.app")


@section("content")
    <!-- BEGIN: Content -->
    <div class="content">
        <div id="App" v-cloak>
            <div class="grid grid-cols-12 gap-4 mt-5">
                <div class="intro-y col-span-12 flex flex-wrap xl:flex-nowrap items-center mt-2">
                    <a href="javascript:void(0);" data-tw-toggle="modal" data-tw-target="#modal-add-on" class="btn btn-primary shadow-md"> <i class="w-2 h-2 mr-2" data-lucide="plus"></i> Créer un nouveau communiqué</a>
                    <div class="hidden xl:block mx-auto text-slate-500"></div>
                    <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0">
                        <div class="mr-2">FILTER PAR DATE</div>
                        <div class="w-40 relative text-slate-500">
                            <input type="date" v-model="filter_date" @input="filter_site=''" class="form-control">
                        </div>
                        <button class="btn btn-outline-primary ml-2" v-if="filter_date !==''" @click="filter_date=''">Tout</button>
                    </div>
                </div>


                <!-- BEGIN: Important Notes -->
                <div class="col-span-12 md:col-span-12 xl:col-span-12">
                    <div class="intro-x flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-auto">
                            Liste des communiqués
                        </h2>
                    </div>
                    <div class="grid grid-cols-12 gap-1">
                        <div class="mt-5 intro-x col-span-12 md:col-span-12 xl:col-span-12" v-for="data in allAnnounces">
                            <div class="box">
                                <div id="important-notes">
                                    <div class="p-5">
                                        <div class="text-base font-medium truncate uppercase">@{{ data.title }}</div>
                                        <div class="mt-1 flex">
                                            <span class="mr-5 text-slate-400">@{{ data.created_at }}</span>
                                            <span class="text-primary uppercase font-medium" v-if="data.site"> @{{ data.site.name }}</span>
                                        </div>
                                        <div class="text-slate-500 text-justify mt-1">@{{ data.content }}</div>
                                        <div class="font-medium flex mt-5">
                                            <div></div>
                                            <button type="button" :disabled="delete_id === data.id" @click.prevent="deleteAnnounce(data.id)" class="btn btn-outline-secondary py-1 px-2 ml-auto ml-auto"> <span v-show="delete_id === data.id"><i data-loading-icon="oval" class="w-4 h-4 mr-2"></i> </span> Retirer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12" v-if="allAnnounces.length ===0">
                            <div class="h-64 flex items-center">
                                <div class="mx-auto text-center">
                                    <div class="flex items-center flex-col">
                                        <i data-lucide="alert-octagon" class="text-pending w-12 h-12 mb-3"></i>
                                        <span>Aucune communiqué répertorié !</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- END: Important Notes -->
                <!-- BEGIN: modal create -->
                <div id="modal-add-on" class="modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form @submit.prevent="createAnnounce" method="POST" action="{{ route('announce.create') }}" class="modal-content form-announce">
                            <!-- BEGIN: Modal Header -->
                            <div class="modal-header">
                                <h2 class="font-medium text-base mr-auto">
                                    Création du nouveau communiqué
                                </h2>
                            </div>
                            <!-- END: Modal Header -->
                            <!-- BEGIN: Modal Body -->
                            <div class="modal-body">
                                <div class="grid grid-cols-12 gap-2 gap-y-1">
                                    <div class="input-form col-span-12">
                                        <label for="validation-form-1" class="form-label w-full flex flex-col sm:flex-row">Titre <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-slate-500">*</span> </label>
                                        <input id="validation-form-1" v-model="form.title" type="text" name="code" class="form-control" placeholder="Entrer le code du site" minlength="2" required>
                                    </div>

                                    <div class="input-form col-span-12">
                                        <label for="validation-form-6" class="form-label w-full flex flex-col sm:flex-row"> Contenu <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-slate-500">*</span> </label>
                                        <textarea id="validation-form-6" v-model="form.content" class="form-control" name="adresse" placeholder="Entrer le contenu du communiqué..." minlength="10" required></textarea>
                                    </div>
                                    <div class="input-form col-span-12">
                                        <label for="validation-form-6" class="form-label w-full flex flex-col sm:flex-row"> Site concerné(optionnel si le communiqué sera destiné à tous les sites) <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-slate-500">*</span> </label>
                                        <select class="form-select" v-model="form.site_id">
                                            <option value="" hidden selected>Sélectionnez un site</option>
                                            <option value="">Pour tous les site</option>
                                            <option v-for="item in allSites" :value="item.id">@{{ item.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- END: Modal Body -->
                            <!-- BEGIN: Modal Footer -->
                            <div class="modal-footer">
                                <button id="btn-reset" type="button" @click.prevent="reset" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Fermer</button>
                                <button :disabled="isLoading" type="submit" class="btn btn-primary mt-5">Créer <span v-if="isLoading"><i data-loading-icon="oval" data-color="white" class="w-4 h-4 ml-2"></i> </span></button>
                            </div>
                            <!-- END: Modal Footer -->

                            <!-- BEGIN: Success Notification Content -->
                            <div id="success-notification-content" class="toastify-content hidden flex">
                                <i class="text-success" data-lucide="check-circle"></i>
                                <div class="ml-4 mr-4">
                                    <div class="font-medium">Opération effectuée !</div>
                                    <div class="text-slate-500 mt-1"> la création d'un nouveau communiqué effectuée ! </div>
                                </div>
                            </div>
                            <!-- END: Success Notification Content -->

                            <!-- BEGIN: Failed Notification Content -->
                            <div id="failed-notification-content" class="toastify-content hidden flex">
                                <i class="text-danger" data-lucide="x-circle"></i>
                                <div class="ml-4 mr-4">
                                    <div class="font-medium">Echec de traitement de la requête!</div>
                                    <div class="text-slate-500 mt-1" v-if="error">@{{ error }} </div>
                                </div>
                            </div>
                            <!-- END: Failed Notification Content -->
                        </form>
                    </div>
                </div>
                <!-- END: modal create -->
            </div>

        </div>

        <div class="h-full flex items-center" id="loader">
            <div class="mx-auto text-center">
                <div>
                    <img src="{{ asset('assets/images/loading.gif') }}" class="w-12 h-12" />
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content -->
@endsection

@push("scripts")
    <script type="module" src="{{ asset("assets/js/scripts/announces.js") }}"></script>
@endpush
