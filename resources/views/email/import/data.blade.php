@component('mail::message')

    {{$data['heading']}}

@component('mail::table')
    | Details       | Count         |
    | :--------- | :-------------: |
    | Total Customer | {{ $data['totalDealer'] }} |
    | Successfully Imported Customer | {{ $data['totalSuccessDealer'] }} |
    | Total Failed Customer | {{ $data['totalFailDealer'] }} |
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
