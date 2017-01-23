@extends('admin.layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h5 class="page-header">{{ trans('common.usersList') }}</h5>

            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('common.search') }}</div>
                <div class="panel-body">
                    <form action="{{ route('admin.users.index') }}" method="get" class="form-inline">
                        <div class="form-group">
                            <label for="" class="control-label">{{ trans('common.id') }}</label>
                            <input type="text" class="form-control" name="id" value="{{ Request::input('id') }}" />
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">{{ trans('common.email') }}</label>
                            <input type="text" class="form-control" name="email" value="{{ Request::input('email') }}" />
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">{{ trans('common.name') }}</label>
                            <input type="text" class="form-control" name="name" value="{{ Request::input('name') }}" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('common.search') }}</button>
                        </div>

                        {!! csrf_field() !!}
                    </form>
                </div>
            </div>
            <table class="table table-hover table-striped table-bordered table-condensed">
                <thead>
                <th>{{ trans('common.id') }}</th>
                <th>{{ trans('common.email') }}</th>
                <th>{{ trans('common.name') }}</th>
                <th>{{ trans('common.edit') }}</th>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->name }}</td>
                        <td>
                            <div class="btn btn-primary">{{ trans('common.modify') }}</div>
                            <div class="btn btn-danger">{{ trans('common.del') }}</div>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            <div class="text-center">{{ $users->links() }}</div>
        </div>
    </div>
@endsection
