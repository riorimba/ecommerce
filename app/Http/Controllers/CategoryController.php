<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;

class CategoryController extends Controller
{
    public function index(){
        return view('categories.index');
    }

    public function getCategories(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('categories.show', $row->id).'" class="show btn btn-success btn-sm">Show</a> ';
                    $btn .= '<a href="'.route('categories.edit', $row->id).'" class="edit btn btn-warning btn-sm">Edit</a> ';
                    $btn .= '<form action="'.route('categories.destroy', $row->id).'" method="POST" style="display:inline-block;">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '<button type="submit" class="btn btn-danger btn-sm">Delete</button>';
                    $btn .= '</form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create(){
        return view('categories.create');
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($validatedData);

        return redirect()->route('categories.index')
                         ->with('success', 'Category created successfully.');
    }

    public function show(Category $category){
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category){
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validatedData);

        return redirect()->route('categories.index')
                         ->with('success', 'Category updated successfully.');
    }


    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Category deleted successfully.');
    }

    public function export(){
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Excel::import(new CategoriesImport, $request->file('file'));

        return redirect()->route('categories.index')->with('success', 'Categories imported successfully.');
    }

    public function template()
    {
        return response()->download(storage_path('app/templates/categories_template.xlsx'));
    }
}
