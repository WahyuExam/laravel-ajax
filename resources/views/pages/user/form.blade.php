{!! Form::model($model,[
    'route' => $model->exists ? ['contact.update',$model->id] : 'contact.store',
    'method'=> $model->exists ? 'PUT' : 'POST'
])!!}

    <div class="form-group">
        <label for="name" class="label-control">Nama</label>
        {!! Form::text('name',null,['class' => 'form-control', 'id' => 'name']) !!}
    </div>

    <div class="form-group">
        <label for="name" class="label-control">Email</label>
        {!! Form::email('email',null,['class' => 'form-control', 'id' => 'email']) !!}
    </div>

{!! Form::close() !!}