<?php

namespace App\Http\Controllers\Admin;

use App\Models\Studios;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\Admin\StudiosRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class StudiosController extends Controller
{
    use MediaUploadingTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('studios_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $studios = Studios::all();

        return view('admin.studios.index', compact('studios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('studios_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.studios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudiosRequest $request)
    {
        $uploadedImages = $request->file('image');

        if ($uploadedImages) {
            $imagePaths = [];
            foreach ($uploadedImages as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('image-studios', $imageName, 'public');
                $imagePaths[] = $imageName;
            }
        
        abort_if(Gate::denies('studios_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       $service = Studios::create([
        'names' => $request->input('names'),
        'price' => $request->input('price'),
        'org' => $request->input('org'),
        'image' => json_encode($imagePaths),
        'status' => $request->input('status', 0),
         ]);
      

        return redirect()->route('admin.studios.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Studios $studio)
    {
        abort_if(Gate::denies('studios_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.studios.show', compact('studio'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Studios $studio)
    {
        abort_if(Gate::denies('studios_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.studios.edit', compact('studio'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudiosRequest $request,$id)
    {

        $service = Studios::findOrFail($id);

        $uploadedImages = $request->file('image');
        if ($uploadedImages) {
            $imagePaths = [];
            foreach ($uploadedImages as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('image-studios', $imageName, 'public');
                $imagePaths[] = $imageName;
            }
            $oldImages = json_decode($service->image, true);
            foreach ($oldImages as $oldImage) {
                if (!in_array($oldImage, $imagePaths)) {
                    Storage::disk('public')->delete('image-studios/' . $oldImage);
                }
            }
            $service->image = json_encode($imagePaths);
        }

        $service->names = $request->input('names');
        $service->price = $request->input('price');
        $service->org = $request->input('org');    
        $service->status = $request->input('status', 0);

        $service->save();

        return redirect()->route('admin.studios.index')->with([
            'message' => 'Successfully updated!',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Studios $studio)
    {
        abort_if(Gate::denies('studios_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $studio->delete();

        return redirect()->route('admin.studios.index')->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Delete all selected Permission at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('studios_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Studios::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
