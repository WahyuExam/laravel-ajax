@extends('layouts.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">Data Kontak</div>
        <div class="pull-right" style="margin-top:-27px; margin-right:-9px;">
            <a href="{{ route('contact.create') }}" class="btn btn-primary modal-show" title="Tambah Data">Tambah Data</a>
        </div>
    </div>

    <div class="panel-body">
        <table id="datatable" class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kontak</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
            </tbody>
            
        </table>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function(){
            $('#datatable').dataTable({
                processing : true,
                serveSide : true,
                ajax : "{{ route('api.contact') }}",
                columns : [
                    {'data' : 'DT_RowIndex', 'name' : 'id'},
                    {'data' : 'name', 'name' : 'name'},
                    {'data' : 'email', 'name' : 'email'},
                    {'data' : 'action', 'name' : 'action'},
                ]
            });

            $('body').on('click', '.modal-show', function(e){
                e.preventDefault();
                var me = $(this),
                    title = me.attr('title'),
                    url = me.attr('href'); 
                
                $('#modal-title').text(title);
                $('#btn-save').removeClass('hide').text(me.hasClass('edit') ? 'Ubah' : 'Simpan');

                $.ajax({
                    url : url,
                    dataType : 'html',
                    success : function(response){
                        $('#modal-body').html(response);
                    }
                })
                
                $('#modal').modal('show');
            });

            $('body').on('click', '#btn-save', function(){
                var form = $('#modal-body form'),
                    url = form.attr('action'),
                    method = $('input[name=_method').val()==undefined ? 'POST' : 'PUT'
                
                form.find('.help-block').remove();
                form.find('.form-group').removeClass('has-error');

                $.ajax({
                    url : url,
                    type : method,
                    data : form.serialize(),
                    success : function(response){
                        form.trigger('reset');
                        $('#modal').modal('hide');
                        $('#datatable').DataTable().ajax.reload();

                        swal({
                            type : 'success',
                            title : method=="POST" ? 'Data Berhasil Disimpan' : 'Data Berhasil Diubah'
                        })
                    },
                    error : function(xhr){
                        var err = xhr.responseJSON;
                        console.log(err);
                        if ($.isEmptyObject(err)==false){
                            $.each(err.errors, function(key, value){
                                $('#'+key).closest('.form-group').addClass('has-error').append('<span class="help-block">'+ value +'</span>');
                            })
                        }
                    }
                })
            });

            $('body').on('click', '.btn-hapus', function(e){
                e.preventDefault();
                var me = $(this),
                    url = me.attr('href'),
                    token = $('meta[name=csrf-token]').attr('content');
         
                swal({
                    type : 'warning',
                    title : 'Apakah Data Akan Dihapus ?',
                    showCancelButton : true, 
                    cancelButtonColor : 'red',
                    cancelButtonText : 'Tidak'
                }).then(result=>{
                    if(result.value){
                        $.ajax({
                            url : url,
                            type : 'POST',
                            data : {
                                '_method' : 'DELETE',
                                '_token'  : token
                            },
                            success : function(){
                                $('#datatable').DataTable().ajax.reload();

                                swal({
                                    type : 'success',
                                    title : 'Data Berhasil Dihapus'
                                })
                            },
                        })
                    }
                })
            })

            $('body').on('click', '.btn-show', function(e){
                e.preventDefault();
                var me = $(this),
                    url = me.attr('href'),
                    title = me.attr('title');

                $('#modal-title').text(title);
                $('#btn-save').addClass('hide');
                $.ajax({
                    url : url,
                    dataType : 'html',
                    success : function(response){
                        $('#modal-body').html(response);
                    }
                })
                $('#modal').modal('show');
            })
        })
    </script>
@endpush