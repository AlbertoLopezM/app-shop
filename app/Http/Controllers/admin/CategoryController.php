<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Category;
use File;

class CategoryController extends Controller
{
    public function index()
    {
    	$categories = Category::orderBy('name')->paginate(10);
    	return view('admin.categories.index')->with(compact('categories'));
    }

    public function create()
    {
    	return view('admin.categories.create'); //formulario de registro
    }

    public function store(Request $request)
    {
    	//registrar el nuevo producto de la bd
    	//validar

    	
    	$this->validate($request, Category::$rules, Category::$messages);

    	$category = Category::create($request->only('name', 'description')); 
    	 
        if($request->hasFile('image')) 
        {
            $file = $request->file('image');
            $path = public_path() . '/images/categories';
            $fileName = uniqid() . '-' .$file->getClientOriginalName();
            $moved = $file->move($path, $fileName);
            

            //update category
            if($moved)
            {
                $category->image = $fileName;
                $category->save(); //INSERT
            }
        }

    	return redirect('/admin/categories');
    }

    public function edit(Category $category)
    {
    	//return "Mostrar aqui el form de edición para el producto con id $id";
    	
    	return view('admin.categories.edit')->with(compact('category')); //formulario de registro
    }

    public function update(Request $request, Category $category)
    {
    	//registrar el nuevo producto de la bd
    	//dd($request->all());

    	

    	$this->validate($request, Category::$rules, Category::$messages);
    	$category->update($request->only('name', 'description'));

         if($request->hasFile('image')) 
        {
            $file = $request->file('image');
            $path = public_path() . '/images/categories';
            $fileName = uniqid() . '-' .$file->getClientOriginalName();
            $moved = $file->move($path, $fileName);
            

            //update category
            if($moved)
            {
                $previousPath = $path . '/' . $category->image;

                $category->image = $fileName;
                $saved = $category->save(); //INSERT

                
                if($saved)
                    File::delete($previousPath);
            }
        }

    	return redirect('/admin/categories');
    }

    public function destroy(Category $category)
    {
    	$category->delete(); //DELETE
    	return back();
    }
}
