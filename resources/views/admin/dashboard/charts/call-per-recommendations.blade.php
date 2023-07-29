<x-admin-card :title="__('Call Per Recommendation')" class="col-sm-6 calls-per-recommendation">
   <div class="calls-recommendations">
        <ul class="nav nav-tabs nav-top-border no-hover-bg" role="tablist">
            <li class="nav-item">
                <a
                    class="nav-link active"
                    href="#specialityTab"
                    id="speciality-tab"
                    data-toggle="tab"
                    role="tab"
                >
                    <i class="fa fa-align-justify"></i>
                    {{ __('Speciality') }}
                </a>
            </li>
            <li class="nav-item">
                <a
                    href="#radiologyTab"
                    id="radiology-tab"
                    data-toggle="tab"
                    class="nav-link"
                    role="tab"
                >
                    <i class="fa fa-align-justify"></i>
                    {{ __('Radiology') }}
                </a>
            </li>
            <li class="nav-item">
                <a
                    href="#laboratoryTab"
                    id="laboratory-tab"
                    data-toggle="tab"
                    class="nav-link"
                    role="tab"
                >
                    <i class="fa fa-align-justify"></i>
                    {{ __('Laboratory') }}
                </a>
            </li>
        </ul>

        <div class="tab-content px-1 pt-1 pb-1">
            <div
                aria-labelledby="speciality-tab"
                class="tab-pane active"
                id="specialityTab"
                role="tabpanel"
            >
                @if($callsPerSpeciality->count() > 0)
                    @foreach($callsPerSpeciality as $speciality)
                        <div class="recommendation-name">
                            {{ $speciality->name }}
                        </div>
                        <div class="progress progress-sm mt-1 mb-0 recommendation-progress-bar">
                            <div class="percentage">{{ ceil($speciality->recommendation_count / $callsSpecialityCount * 100) }}% ({{ $speciality->recommendation_count }})</div>
                            <div
                                style="width: {{ ceil($speciality->recommendation_count / $callsSpecialityCount * 100) }}%"
                                aria-valuenow="{{ ceil($speciality->recommendation_count / $callsSpecialityCount * 100) }}"
                                class="progress-bar bg-success"
                                aria-valuemax="100"
                                role="progressbar"
                                aria-valuemin="0"
                            >
                            </div>
                        </div>
                    @endforeach
                @else
                    {{ __('No Speciality Recommendation') }}
                @endif
            </div>
            <div
                aria-labelledby="radiology-tab"
                id="radiologyTab"
                class="tab-pane"
                role="tabpanel"
            >
                @if($callsPerRadiology->count() > 0)
                    @foreach($callsPerRadiology as $radiology)
                        <div class="recommendation-name">
                            {{ $radiology->name }}
                        </div>
                        <div class="progress progress-sm mt-1 mb-0 recommendation-progress-bar">
                            <div class="percentage">{{ ceil($radiology->recommendation_count / $callsRadiologyCount * 100) }}% ({{ $radiology->recommendation_count }})</div>
                            <div
                                style="width: {{ ceil($radiology->recommendation_count / $callsRadiologyCount * 100) }}%"
                                aria-valuenow="{{ ceil($radiology->recommendation_count / $callsRadiologyCount * 100) }}"
                                class="progress-bar bg-success"
                                aria-valuemax="100"
                                role="progressbar"
                                aria-valuemin="0"
                            >
                            </div>
                        </div>
                    @endforeach
                @else
                    {{ __('No Radiology Recommendation') }}
                @endif
            </div>
            <div
                aria-labelledby="laboratory-tab"
                id="laboratoryTab"
                class="tab-pane "
                role="tabpanel"
            >
                @if($callsPerLaboratory->count() > 0)
                    @foreach($callsPerLaboratory as $laboratory)
                        <div class="recommendation-name">
                            {{ $laboratory->name }}
                        </div>
                        <div class="progress progress-sm mt-1 mb-0 recommendation-progress-bar">
                            <div class="percentage">{{ ceil($laboratory->recommendation_count / $callsLaboratoryCount * 100) }}% ({{ $laboratory->recommendation_count }})</div>
                            <div
                                style="width: {{ ceil($laboratory->recommendation_count / $callsLaboratoryCount * 100) }}%"
                                aria-valuenow="{{ ceil($laboratory->recommendation_count / $callsLaboratoryCount * 100) }}"
                                class="progress-bar bg-success"
                                aria-valuemax="100"
                                role="progressbar"
                                aria-valuemin="0"
                            >
                            </div>
                        </div>
                    @endforeach
                @else
                    {{ __('No Laboratory Recommendation') }}
                @endif
            </div>

        </div>
    </div>
</x-admin-card>
