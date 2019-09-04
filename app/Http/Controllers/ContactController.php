<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use yajra\DataTables\Datatables;

use App\model\Contact;

class ContactController extends Controller
{
    
    public function index()
    {
        return view('pages.user.index');
    }

   
    public function create()
    {
        $model = new Contact();
        return view('pages.user.form',compact('model'));
    }

   
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'  => 'required',
            'email' => 'required|email|unique:contacts,email'
        ],[
            'name.required' => 'Nama Diisi Bro',
            'email.required'=> 'Email Diisi Bro',
            'email.email'   => 'Format Email Salah Bro',
            'email.unique'  => 'Email Sudah Ada, Pakai Email Yang Lain Bro'
        ]);

        Contact::create($request->all());
    }

  
    public function show($id)
    {
       $model = Contact::findOrFail($id);
       return view('pages.user.show',compact('model'));
    }

    
    public function edit($id)
    {
        $model = Contact::findOrFail($id);
        return view('pages.user.form',compact('model'));
    }

   
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'  => 'required',
            'email' => 'required|email|unique:contacts,email,'.$id
        ],[
            'name.required' => 'Nama Diisi Bro',
            'email.required'=> 'Email Diisi Bro',
            'email.email'   => 'Format Email Salah Bro',
            'email.unique'  => 'Email Sudah Ada, Pakai Email Yang Lain Bro'
        ]);
        
        $model = Contact::findOrFail($id);
        $model->update($request->all());
    }

    
    public function destroy($id)
    {
        $model = Contact::findOrFail($id);
        $model->delete();
    }

    public function apiContact(){
        $model = Contact::query();
        return DataTables::of($model)
            ->addColumn('action',function($model){
                return view('layouts._action',[
                    'model' => $model,
                    'url_show'  => route('contact.show',$model->id),
                    'url_edit'  => route('contact.edit',$model->id),
                    'url_hapus' => route('contact.destroy',$model->id),
                ]);
            })
            ->addIndexColumn()
            ->make(true);
    }       
}
