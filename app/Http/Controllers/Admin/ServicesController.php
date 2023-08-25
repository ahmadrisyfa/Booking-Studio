<?php

namespace App\Http\Controllers\Admin;

use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\Admin\ServicesRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
class ServicesController extends Controller
{
    use MediaUploadingTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('services_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $services = Services::all();

        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('services_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.services.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $uploadedImages = $request->file('image');

        // Jika ada file yang diunggah
        if ($uploadedImages) {
            $imagePaths = [];
            foreach ($uploadedImages as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('image-services', $imageName, 'public');
                $imagePaths[] = $imageName;
            }
        
        abort_if(Gate::denies('services_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       $service = Services::create([
        'name' => $request->input('name'),
        'price' => $request->input('price'),
        'jenis_paket' => $request->input('jenis_paket'),
        'jam_paket' => $request->input('jam_paket'),
        'image' => json_encode($imagePaths),
        'denda' => $request->input('denda'),
        'status' => $request->input('status', 0),
        'deskripsi' => nl2br($request->input('deskripsi')),

    ]);
      

        return redirect()->route('admin.services.index')->with([
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
    public function show(Services $service)
    {
        abort_if(Gate::denies('services_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Services $service)
    {
        abort_if(Gate::denies('services_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServicesRequest $request,$id)
    {

        $service = Services::findOrFail($id);

        $uploadedImages = $request->file('image');
        if ($uploadedImages) {
            $imagePaths = [];
            foreach ($uploadedImages as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('image-services', $imageName, 'public');
                $imagePaths[] = $imageName;
            }
            $oldImages = json_decode($service->image, true);
            foreach ($oldImages as $oldImage) {
                if (!in_array($oldImage, $imagePaths)) {
                    Storage::disk('public')->delete('image-services/' . $oldImage);
                }
            }
            $service->image = json_encode($imagePaths);
        }

        $service->name = $request->input('name');
        $service->price = $request->input('price');
        $service->jenis_paket = $request->input('jenis_paket');
        $service->jam_paket = $request->input('jam_paket');
        $service->denda = $request->input('denda');
        $service->status = $request->input('status', 0);
        $service->deskripsi = nl2br($request->input('deskripsi'));

        $service->save();

        return redirect()->route('admin.services.index')->with([
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
    public function destroy(Services $service)
    {
        abort_if(Gate::denies('services_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    
        $imagePaths = json_decode($service->image, true);
        foreach ($imagePaths as $imagePath) {
            Storage::disk('public')->delete('image-services/' . $imagePath);
        }
    
        $service->delete();
    
        return redirect()->route('admin.services.index')->with([
            'message' => 'Successfully deleted!',
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
        abort_if(Gate::denies('services_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Services::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
