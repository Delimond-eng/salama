@extends("layouts.app")


@section("content")
<!-- BEGIN: Content -->
<div class="content">
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Les rapports des patrouilles
        </h2>
    </div>
    <div id="App" v-cloak>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 flex flex-wrap xl:flex-nowrap items-center mt-2">
                <a href="#"></a>
                <div class="hidden xl:block mx-auto text-slate-500"></div>
                <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0">
                    <div class="w-56 relative text-slate-500">
                        <select class="form-select" @change="filter_date=''" v-model="filter_site">
                            <option value="" hidden selected>Filtrez par site.</option>
                            <option value="">Tous les rapports</option>
                            <option v-for="item in allSites" :value="item.id">@{{ item.name }}</option>
                        </select>
                    </div>
                    <div class="ml-6 mr-2">| PAR DATE</div>
                    <div class="w-40 relative text-slate-500">
                        <input type="date" v-model="filter_date" @input="filter_site=''" class="form-control">
                    </div>

                </div>
            </div>
            <!-- BEGIN: Data List -->
            <div class="intro-y col-span-12 overflow-auto 2xl:overflow-visible">
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">NOM & CODE DU SITE</th>
                            <th class="text-center whitespace-nowrap">DATE & HEURE DEBUT</th>
                            <th class="text-center whitespace-nowrap">DATE & HEURE FIN</th>
                            <th class="text-center whitespace-nowrap">NOM & MATRICULE AGENT</th>
                            <th class="text-center whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="intro-x" v-for="data in allReports">
                            <td class="concat">
                                <a href="javascript:void(0);" class="font-medium whitespace-nowrap">@{{ data.site.name }}</a>
                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">@{{ data.site.code }}
                                </div>
                            </td>
                            <td class="text-center">@{{data.started_at}}</td>
                            <td class="text-center">@{{data.ended_at}}</td>
                            <td>
                                <a href="javascript:void(0);" class="font-medium whitespace-nowrap">@{{ data.agent.fullname }}</a>
                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">@{{ data.agent.matricule }}
                                </div>
                            </td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center mr-3 text-primary" href="javascript:;" @click="selectedPatrol = data " data-tw-toggle="modal" data-tw-target="#modal-details"> <i data-lucide="external-link" class="w-4 h-4 mr-1"></i>Voir détails </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-span-12" v-if="allReports.length === 0">
                <div class="h-64 flex items-center">
                    <div class="mx-auto text-center">
                        <div class="flex items-center flex-col">
                            <i data-lucide="alert-octagon" class="text-pending w-12 h-12 mb-3"></i>
                            <span>Aucun Rapport de patrouille répertorié !</span>
                        </div>
                    </div>
                </div>
            </div>


            <!-- BEGIN: modal details -->
            <div id="modal-details" class="modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content bg-slate-100">

                        <div class="modal-header">
                            <h2 class="font-medium text-base mr-auto">
                                Rapport patrouille détails
                            </h2>
                        </div>
                        <!-- BEGIN: Modal Body -->
                        <div class="modal-body">
                            <div class="grid grid-cols-12 gap-2 gap-y-1" v-if="selectedPatrol">

                                <div class="col-span-12 md:col-span-12" v-if="selectedPatrol.comment_text">
                                    <h3 class="mb-2 text-xl font-medium leading-none">Commentaire de l'agent</h3>
                                    <p class="text-slate-600">@{{ selectedPatrol.comment_text }}</p>
                                </div>

                                <div class="col-span-12 md:col-span-12">
                                    <div class="mt-5 relative before:block before:absolute before:w-px before:h-[85%] before:bg-slate-200 before:dark:bg-darkmode-400 before:ml-5 before:mt-5">
                                        <div class="intro-x relative flex items-center mb-3" v-for="data in selectedPatrol.scans">
                                            <div class="before:block before:absolute before:w-20 before:h-px before:bg-slate-200 before:dark:bg-darkmode-400 before:mt-5 before:ml-5">
                                                <div class="w-10 h-10 flex-none image-fit overflow-hidden">
                                                    <img alt="icon" src="assets/images/area-2.png">
                                                </div>
                                            </div>
                                            <div class="box px-5 py-3 ml-4 flex-1 zoom-in">
                                                <div class="flex items-center">
                                                    <div class="font-medium" v-if="data.area">@{{ data.area.libelle }}</div>
                                                    <div class="text-xs text-slate-500 ml-auto">
                                                        <span>@{{ data.time }}</span>
                                                    </div>
                                                </div>
                                                <div class="text-slate-500 mt-1"><span>Distance : @{{ data.status }}</span> | <span class="font-semibold">Agent : @{{ data.agent.matricule }} - @{{ data.agent.fullname }}</span>

                                                </div>
                                                <h6 class="font-medium leading-none mt-3" v-if="data.comment">Remarque</h6>
                                                <p class="mt-1" v-if="data.comment">@{{ data.comment }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END: Modal Body -->
                        <!-- BEGIN: Modal Footer -->
                        <div class="modal-footer">
                            <button id="btn-reset" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Fermer</button>
                        </div>
                        <!-- END: Modal Footer -->
                    </div>
                </div>
            </div>
            <!-- END: modal details -->
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
<script type="module" src="{{ asset("assets/js/scripts/reports.js") }}"></script>
@endpush
