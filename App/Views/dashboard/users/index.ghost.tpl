{{ extends('layouts.master') }}

#set[content]
<div class="panel panel-default">
    <div class="panel-body">
        <h1>
            Lista de usuarios
            <a class="btn btn-success" href="{{ url('/dashboard/users/form') }}">
                <span class="glyphicon glyphicon-plus"></span>
            </a>
        </h1>
        <div id="container-user-table" class="container-table-data" data-update="#container-user-table:.container-pagination,tbody #messages">
            <div class="row clearfix">
                <div class="col-sm-3">
                    <div class="container-hide-columns btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Columnas <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li class="checkbox">
                                <label>
                                    <input type="checkbox" name="user_name">
                                    Nombre
                                </label>
                            </li>
                            <li class="checkbox">
                                <label>
                                    <input type="checkbox" name="user_login">
                                    Login
                                </label>
                            </li>
                            <li class="checkbox">
                                <label>
                                    <input type="checkbox" name="user_email">
                                    Email
                                </label>
                            </li>
                            <li class="checkbox">
                                <label>
                                    <input type="checkbox" name="user_registered">
                                    Fecha
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-9">
                    <form class="form-search-table-data">
                        <div class="input-group">
                            <input class="form-control" type="text" name="search-value" placeholder="login:admin email:info@softn.red">
                            <span class="input-group-addon">
                                <input type="checkbox" name="search-strict">
                            </span>
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">Buscar</button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-sm-12">
                    {{ component('pagination') }}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th data-column="user_name">Nombre</th>
                        <th data-column="user_login">Login</th>
                        <th data-column="user_email">Email</th>
                        <th data-column="user_registered">Fecha de registro</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>Login</th>
                        <th>Email</th>
                        <th>Fecha de registro</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    #foreach($users as $user)
                    <tr>
                        <td>
                            <a class="btn btn-primary" href="{{ url('/dashboard/users/form/') }}{{$user->id}}">
                                <span class="glyphicon glyphicon-edit"></span>
                            </a>
                            <button class="btn btn-danger" type="button" data-delete-id="{{$user->id}}" data-toggle="modal" data-target="#modal-delete" data-delete-action="/dashboard/users/delete">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </td>
                        <td>{{$user->user_name}}</td>
                        <td>{{$user->user_login}}</td>
                        <td>{{$user->user_email}}</td>
                        <td>{{$user->user_registered}}</td>
                    </tr>
                    #endforeach
                    </tbody>
                </table>
            </div>
            {{ component('pagination') }}
        </div>
        <br/>
        <hr/>
        <br/>
        <div id="container-user-table-test" class="container-table-data" data-update="#container-user-table-test:.container-pagination,tbody">
            {{ component('pagination') }}
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th data-column="user_name">Nombre</th>
                        <th data-column="user_login">Login</th>
                        <th data-column="user_email">Email</th>
                        <th data-column="user_registered">Fecha de registro</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>Login</th>
                        <th>Email</th>
                        <th>Fecha de registro</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    #foreach($users as $user)
                    <tr>
                        <td>
                            <a class="btn btn-primary" href="{{ url('/dashboard/users/form/') }}{{$user->id}}">
                                <span class="glyphicon glyphicon-edit"></span>
                            </a>
                            <button class="btn btn-danger" type="button" data-delete-id="{{$user->id}}" data-toggle="modal" data-target="#modal-delete" data-delete-action="/dashboard/users/delete" data-update="#container-user-table-test #messages">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </td>
                        <td>{{$user->user_name}}</td>
                        <td>{{$user->user_login}}</td>
                        <td>{{$user->user_email}}</td>
                        <td>{{$user->user_registered}}</td>
                    </tr>
                    #endforeach
                    </tbody>
                </table>
            </div>
            {{ component('pagination') }}
        </div>
    </div>
</div>
{{ include('includes.modaldelete') }}
#end

#set[scripts]
<script src="{{ asset('js/modal-dialog.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/data-list.js') }}" type="text/javascript"></script>
#end
