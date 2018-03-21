{{ extends('layouts.master') }}

#set[content]
<div class="panel panel-default">
    <div class="panel-body">
        <h1>
            Lista de usuarios
        </h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th></th>
                    <th>Nombre</th>
                    <th>Login</th>
                    <th>Email</th>
                    <th>Fecha de registro</th>
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
                        <button class="btn btn-primary" type="button">
                            <span class="glyphicon glyphicon-edit"></span>
                        </button>
                        <button class="btn btn-danger" type="button">
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
    </div>
</div>
#end
