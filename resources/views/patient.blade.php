@extends('index')

@section('content')
    @livewire('patient-records')
    @livewire('PatientFormController.patient-form-modal')
    @livewire('appointment-history-modal')
@endsection