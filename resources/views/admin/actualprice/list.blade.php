@extends('admin.layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h5 class="page-header">{{ trans('common.actualPriceList') }}</h5>

            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('common.search') }}</div>
                <div class="panel-body">
                    <form action="{{ route('admin.prices.store') }}" method="post" class="form-inline" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="" class="control-label">{{ trans('common.excel') }}</label>
                            <input type="file" class="form-control" name="excel" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('common.upload') }}</button>
                        </div>

                        {!! csrf_field() !!}
                    </form>
                </div>
            </div>
            <table class="table table-hover table-striped table-bordered table-condensed">
                <thead>
                <th>{{ trans('common.id') }}</th>
                <th>{{ trans('common.sigungu') }}</th>
                <th>{{ trans('common.mainNo') }}</th>
                <th>{{ trans('common.subNo') }}</th>
                </thead>
                <tbody>
                @foreach($prices as $price)
                    <tr>
                        <td>{{ $price->id }}</td>
                        <td>{{ $price->sigungu }}</td>
                        <td>{{ $price->main_no }}</td>
                        <td>{{ $price->sub_no }}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            {{--<div class="text-center">{{ $prices->links() }}</div>--}}
        </div>
    </div>
@endsection
