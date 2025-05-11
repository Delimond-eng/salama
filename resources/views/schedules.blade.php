@extends("layouts.app")


@section("content")
<!-- BEGIN: Content -->
<div class="content">
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Planning de patrouille
        </h2>
    </div>
    <div id="App" v-cloak>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 flex flex-wrap xl:flex-nowrap items-center mt-2">
                <a href="javascript:void(0);" class="btn btn-primary shadow-md" data-tw-toggle="modal" data-tw-target="#modal-add"> <i class="w-2 h-2 mr-2" data-lucide="plus"></i> Créer un planning</a>
                <div class="hidden xl:block mx-auto text-slate-500"></div>
                <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0">
                    <div class="w-56 relative text-slate-500">
                        <select class="form-select" v-model="search">
                            <option value="" hidden selected>Filtrez par site...</option>
                            <option value="">Toutes les données.</option>
                            <option v-for="item in allSites" :value="item.id">@{{ item.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- BEGIN: Data List -->
            <div class="intro-y col-span-12 overflow-auto 2xl:overflow-visible">
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">LIBELLE</th>
                            <th class="text-center whitespace-nowrap">SITE</th>
                            <th class="text-center whitespace-nowrap">HEURES</th>
                            <th class="text-center whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="intro-x" v-for="(data, index) in allSchedules">
                            <td class="concat"> @{{ data.libelle }}</td>
                            <td class="!py-3.5">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <a href="javascript:void(0);" class="font-medium whitespace-nowrap">@{{ data.site.name }}</a>
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">@{{ data.site.code }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="concat"> @{{ data.start_time }} -- @{{ data.end_time }}</td>

                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center mr-3 text-danger" href="javascript:;" > <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-span-12" v-if="allSchedules.length === 0">
                <div class="h-64 flex items-center">
                    <div class="mx-auto text-center">
                        <div class="flex items-center flex-col">
                            <i data-lucide="alert-octagon" class="text-pending w-12 h-12 mb-3"></i>
                            <span>Aucun Planning de patrouille répertorié !</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Begin: Modal create schedules -->
        <div id="modal-add" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="createSchedules" method="POST" action="{{ route('schedules.create') }}" class="modal-content form-planning">
                    <!-- BEGIN: Modal Header -->
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">
                            Création planning de patrouille
                        </h2>
                    </div>
                    <!-- END: Modal Header -->
                    <!-- BEGIN: Modal Body -->
                    <div class="modal-body">
                        <div class="grid grid-cols-12 gap-2 gap-y-1">
                            <div class="col-span-12">
                                <label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row"> Site affecté <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-slate-500">*</span> </label>
                                <div class="input-form mt-1 col-span-12">
                                    <select class="form-select w-full" v-model="form.site_id" required>
                                        <option label="Sélectionnez un site concerné" selected hidden></option>
                                        <option v-for="item in allSites" :value="item.id">@{{ item.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-span-12">
                                <div class="grid grid-cols-12 gap-3" v-for="(field, index) in form.schedules">
                                    <div class="input-form mt-1 col-span-6">
                                        <label for="validation-form-2" class="form-label"> Libellé </label>
                                        <input type="text" v-model="field.libelle" class="form-control" placeholder="Libellé" required>
                                    </div>
                                    <div class="input-form mt-1 col-span-3">
                                        <label for="validation-form-2" class="form-label"> Heure Début </label>
                                        <input type="time" v-model="field.start_time" class="form-control" required>
                                    </div>
                                    <div class="input-form mt-1 col-span-3">
                                        <label for="validation-form-2" class="form-label w-full flex flex-col sm:flex-row">Heure Fin
                                            <a v-if="index===0" href="javascript:void(0)" class="btn btn-sm btn-primary-soft sm:ml-auto sm:mt-0 text-xs" style="padding-top: 0px;padding-bottom: 0px;" @click="addField">Ajouter</a>
                                            <a v-else href="javascript:void(0)" class="btn btn-sm btn-danger-soft sm:ml-auto sm:mt-0 text-xs"  style="padding-top: 0px;padding-bottom: 0px;" @click="removeField(field)">Reduire</a>
                                        </label>
                                        <input type="time" v-model="field.end_time" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer">
                        <button id="btn-reset" type="button" @click.prevent="reset" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Fermer</button>
                        <button :disabled="isLoading" type="submit" class="btn btn-primary">Sauvegarder les modifications <span v-if="isLoading"><i data-loading-icon="oval" data-color="white" class="w-4 h-4 ml-2"></i> </span></button>
                    </div>
                    <!-- END: Modal Footer -->

                    <!-- BEGIN: Success Notification Content -->
                    <div id="success-notification-content" class="toastify-content hidden flex">
                        <i class="text-success" data-lucide="check-circle"></i>
                        <div class="ml-4 mr-4">
                            <div class="font-medium">Opération effectuée !</div>
                            <div class="text-slate-500 mt-1"> la création d'un nouveau site de patrouille effectuée ! </div>
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
        <!-- End: Modal create schedules -->
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
<script type="module" src="{{ asset("assets/js/scripts/planning.js") }}"></script>
@endpush
