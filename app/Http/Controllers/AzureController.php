<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AzureBlobService;
use Carbon\Carbon;

class AzureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $azureBlobService;

    public function __construct(AzureBlobService $azureBlobService)
    {
        $this->azureBlobService = $azureBlobService;
    }

    function getErrorValue($string)
    {
        $startDelimiter = "Value: ";
        $endDelimiter = ".";
        $startIndex = strpos($string, $startDelimiter);
        if ($startIndex === false) {
            return false;
        }

        $startIndex += strlen($startDelimiter);
        $endIndex = strpos($string, $endDelimiter, $startIndex);

        if ($endIndex === false) {
            return false;
        }

        $substring = substr($string, $startIndex, $endIndex - $startIndex);

        if ($substring !== false) {
            return $substring;
        } else {
            return '';
        }
    }

    public function index()
    {
        $all_containers = $this->azureBlobService->getContainers();

        $containers = array();
        
        foreach($all_containers as $container){
            $date = $container->getProperties()->getLastModified()->format('d/m/Y H:i:s');
            $access = $container->getProperties()->getPublicAccess() ? $container->getProperties()->getPublicAccess() : 'private';
            $temp = array(
                "name" => $container->getName(),
                "last_modification" => $date,
                "public_access_level" => $access

            );
            array_push($containers, $temp);
        }

        // return $containers_temp;
        // $containers = array(
        //     array(
        //         "name" => "container1",
        //         "last_modification" => "20/07/2023",
        //         "public_access_level" => "Private"
        //     ),
        //     array(
        //         "name" => "container2",
        //         "last_modification" => "19/07/2023",
        //         "public_access_level" => "Bloc"
        //     ),
        //     array(
        //         "name" => "container3",
        //         "last_modification" => "20/07/2023",
        //         "public_access_level" => "Bloc"
        //     ),
        //     array(
        //         "name" => "container4",
        //         "last_modification" => "18/07/2023",
        //         "public_access_level" => "Container"
        //     ),
        //     array(
        //         "name" => "container5",
        //         "last_modification" => "17/07/2023",
        //         "public_access_level" => "Private"
        //     ),
        // );

        $alert = '';
        $alert_type = '';
        if (session()->has('alert') && session()->has('alert_type')){
            $alert = session('alert');
            $alert_type = session('alert_type');
        }
        return view('container.index', compact('containers', 'alert', 'alert_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $alert = '';
        if (session()->has('alert')){
            $alert = session('alert');
        }
        return view('container.create', compact('alert'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'container_name' => 'required|regex:/(^[a-z0-9](?!.*--)[a-z0-9-]{1,61}[a-z0-9]$)/',
            'public_access_level' => 'required'
        ]);

        $response = $this->azureBlobService->addContainer($request->container_name, $request->public_access_level);

        if($response[0]){
            $alert = 'The container was successfully created';
            $alert_type = "alert-success";
            return Redirect('/container')->with(compact('alert', 'alert_type'));
        }else{
            // $alert = '&lt;h4 class=&quot;alert-heading fw-bold&quot;&gt;Error!&lt;/h4&gt;';
            $alert = 'The container was not created! '.$response[1];
            //    <p>Aww yeah, you successfully read this important alert message. This example text is going to run a bit longer so that you can see how spacing within an alert works with this kind of content.</p>
            //     <hr>
            // <p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice and tidy.</p>
              
            return Redirect('/container/create')->with(compact('alert'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $container = $id;
        $response = $this->azureBlobService->isContainerExist($container);
        if($response[0]){
            $all_blobs = $this->azureBlobService->getBlobs($container);
            $blobs = array();

            foreach($all_blobs as $blob){
                $size = number_format(($blob->getProperties()->getContentLength())/1024, 2);
                $temp = array(
                    "name" => $blob->getName(),
                    "created_on" => $blob->getProperties()->getCreationTime()->format('d/m/Y H:i:s'),
                    "blob_tier" => $blob->getProperties()->getAccessTier(),
                    "content_type" => $blob->getProperties()->getContentType(),
                    "size" => $size
                );
                array_push($blobs, $temp);
            }
            $alert = '';
            $alert_type = '';
            if (session()->has('alert') && session()->has('alert_type')){
                $alert = session('alert');
                $alert_type = session('alert_type');
            }
            return view('container.blob.index', compact('container', 'blobs', 'alert', 'alert_type'));
        }else{
            $object = "container";
            return view('container.404', compact('object'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $container = $id;
        $response = $this->azureBlobService->isContainerExist($container);

        if($response[0]){
            $public_access_level = $response[1];
            $alert = '';
            if (session()->has('alert')){
                $alert = session('alert');
            }
            return view('container.edit', compact('container', 'public_access_level', 'alert'));
        }else{
            $object = "container";
            return view('container.404', compact('object'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $container = $id;
        $request->validate([
            'public_access_level' => 'required'
        ]);

        $response = $this->azureBlobService->ChangeContainerPublicAccess($container, $request->public_access_level);

        if($response[0]){
            $alert = 'The public access level of the container was successfully updated';
            $alert_type = "alert-success";
            return Redirect('/container')->with(compact('alert', 'alert_type'));
        }else{
            $alert = 'Error : The public access level of the container was not updated! ' . $response[1];
            return Redirect()->route('container.edit', $id)->with(compact('alert'));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $container = $id;

        $response = $this->azureBlobService->deleteContainer($container);

        if($response[0]){
            $alert = 'The container was successfully deleted';
            $alert_type = "alert-success";
        }else{
            $alert = 'Error : The container was not deleted! ' . $response[1];
            $alert_type = "alert-danger";
        }

        return Redirect('/container')->with(compact('alert', 'alert_type'));

    }

    public function deleteContainers(Request $request){
        $containers = $request->containers;

        $alert = 'The '.count($containers).' containers are deleted! ';
        $alert_type = "alert-success";

        $i = 0;
        foreach($containers as $container){
            $response = $this->azureBlobService->deleteContainer($container);
            if($response[0]){
                $i++;
            }
        }

        if($i < count($containers)){
            $alert = "Error : Some containers are not deleted! Only $i containers are deleted" . $response[1];
            $alert_type = "alert-danger";
        }
        return Redirect('/container')->with(compact('alert', 'alert_type'));
    }

    // Blobs

    public function deleteBlob($container, $blob)
    {
        $response = $this->azureBlobService->isBlobExist($container, $blob);
        if($response){
            $resp = $this->azureBlobService->deleteBlob($container, $blob);
            if($resp[0]){
                $alert = 'The file was successfully deleted! ';
                $alert_type = "alert-success";
            }else{
                $alert = 'The file was not deleted! ';
                $alert_type = "alert-danger";
            }
            return redirect("/container/$container")->with(compact('alert', 'alert_type'));
        }else{
            $object = "blob";
            return view('container.404', compact('object'));
        }
    }

    public function deleteBlobs(Request $request)
    {
        $container = $request->container;
        $blobs = $request->blobs;
        $i = 0;
        foreach($blobs as $blob){
            $response = $this->azureBlobService->deleteBlob($container, $blob);
            if($response[0]){
                $i++;
            }
        }
        if($i<count($blobs)){
            $alert = "Error: Some file are not deleted! - Only $i files are deleted";
            $alert_type = "alert-danger";
        }else{
            $alert = "The $i files are deleted";
            $alert_type = "alert-success";
        }
        return redirect("/container/$container")->with(compact('alert', 'alert_type'));
    }

    public function uploadFilesForm($container)
    {
        $alert = '';
        if (session()->has('alert')){
            $alert = session('alert');
        }
        return view('container.blob.create', compact('container', 'alert'));
    }

    public function uploadFiles(Request $request, $container)
    {
        $request->validate([
            'files.*' => 'required',
            'blob_tier' => 'required'
        ]);
        $blob_tier = $request->blob_tier;
        $len = count($request->file('files'));
        $i = 0;

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {

                $file_name = $file->getClientOriginalName();
                $content = file_get_contents($file->getRealPath());
                $mime_type = $file->getMimeType();
                $response = $this->azureBlobService->createBlob($container, $file_name, $content, $blob_tier, $mime_type);
                
                if($response[0]){
                    $i++;
                }
            }
        }

        
        if($i < $len){
            $alert = "Error : Some files are not uploaded! Only $i files are uploaded" . $response[1];
            return Redirect("/container/{$container}/upload")->with(compact('alert'));
        }else{
            $alert = "The file was successfully uploaded!";
            $alert_type = "alert-success";
            return Redirect("/container/{$container}")->with(compact('alert', 'alert_type'));
        }
        
        
    }

    public function showBlob($container, $blob)
    {
        $response = $this->azureBlobService->isBlobExist($container, $blob);
        if($response){
            [$content, $content_type] = $this->azureBlobService->getBlob($container, $blob);
            return view('container.blob.show', compact('blob', 'content', 'content_type'));
        }else{
            $object = "blob";
            return view('container.404', compact('object'));
        }

    }

    public function downloadFile($container, $blob)
    {
        $response = $this->azureBlobService->isBlobExist($container, $blob);
        if($response){
            $result = $this->azureBlobService->downloadBlob($container, $blob);
            return $result;
        }else{
            $object = "blob";
            return view('container.404', compact('object'));
        }
    }
}
