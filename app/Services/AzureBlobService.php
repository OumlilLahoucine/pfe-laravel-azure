<?php

namespace App\Services;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
use MicrosoftAzure\Storage\Blob\Models\ContainerACL;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\SetBlobPropertiesOptions;
use MicrosoftAzure\Storage\Blob\Models\SetBlobTierOptions;
use MicrosoftAzure\Storage\Blob\Models\AccessTier;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use Illuminate\Http\Response;

class AzureBlobService
{
    protected $blobService;

    public function __construct(){

        $connectionString = sprintf(
            'DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s;EndpointSuffix=core.windows.net',
            env('AZURE_STORAGE_ACCOUNT'),
            env('AZURE_STORAGE_KEY')
        );

        $this->blobService = BlobRestProxy::createBlobService($connectionString);
    }

    // Containers

    public function isContainerExist($container){
        try {
            $response = $this->blobService->getContainerProperties($container);
            return [true, $response->getPublicAccess()];
        } catch (\Throwable $th) {
            return [false, null];
        }
    }
    
    public function getContainers()
    {
        $result = $this->blobService->listContainers();

        return $result->getContainers();
    }

    public function addContainer($container, $public_access_level){

        try {
            if($public_access_level != 'private'){
                $createContainerOptions = new CreateContainerOptions();
                $createContainerOptions->setPublicAccess($public_access_level);

                // Create the container with the specified access policy
                $this->blobService->createContainer($container, $createContainerOptions);
            }else{
                $this->blobService->createContainer($container);
            }
            return [true, null];
        } catch (\Throwable $th) {
            $error_message = $th->getMessage();
            return [false, $error_message];
        }
        
    }

    public function deleteContainer($container)
    {
        try {
            $this->blobService->deleteContainer($container);
            return [true, null];

        } catch (\Throwable $th) {
            $error_message = $th->getMessage();
            return [false, $error_message];
        }
    }

    public function ChangeContainerPublicAccess($container, $public_access_level)
    {   
        try {
            if($public_access_level == 'private')
                $access = null;
            else
                $access = $public_access_level;

            $acl = ContainerACL::create($access);
            $this->blobService->setContainerACL($container, $acl);
            return [true, null];

        } catch (\Throwable $th) {
            $error_message = $th->getMessage();
            return [false, $error_message];
        }
    }

    // Blobs

    public function getBlobs($container)
    {
        $blobs = $this->blobService->listBlobs($container)->getBlobs();
        return $blobs;
    }
    
    public function createBlob($container, $blob, $content, $blob_tier, $content_type){

        try {
            $options = new SetBlobTierOptions();
            $properties = new SetBlobPropertiesOptions();
            
            $options->setAccessTier($blob_tier);
            $properties->setContentType($content_type);

            $this->blobService->createBlockBlob($container, $blob, $content);

            $this->blobService->setBlobProperties($container, $blob, $properties);

            $this->blobService->setBlobTier($container, $blob, $options);

            return [true, null];

        } catch (\Throwable $th) {
            $error_message = $th->getMessage();
            return [false, $error_message];
        }

    }

    public function isBlobExist($container, $blob){
        try {
            $response = $this->blobService->getBlobProperties($container, $blob);
            return true;
        } catch (\Throwable $th) {
            return false;
        }

    }

    public function deleteBlob($container, $blob){

        try {
        $this->blobService->deleteBlob($container, $blob);

        return [true, null];

        } catch (\Throwable $th) {
            $error_message = $th->getMessage();
            return [false, $error_message];
        }
    }

    public function getBlob($container, $blob){

        $result = $this->blobService->getBlob($container, $blob);
        $content = stream_get_contents($result->getContentStream());
        $content_type = $result->getProperties()->getContentType();
        return [$content, $content_type];
    }

    public function downloadBlob($container, $blob){

        try{
        $result = $this->blobService->getBlob($container, $blob);
        $content = $result->getContentStream();
        $content_type = $result->getProperties()->getContentType();

        $headers = [
            'Content-Type' => $content_type,
            'Content-Disposition' => 'attachment; filename="' . $blob . '"',
        ];

        return response()->stream(function () use ($content) {
            fpassthru($content);
        }, 200, $headers);
    }
    catch (ServiceException $e) {
        $code = $e->getCode();
        $error_message = $e->getMessage();
        dd("Error occurred: $error_message"); // Add this line for debugging

        // You can also log the error message for further investigation
        // Log::error("Error occurred: $error_message");
    }
    }

}
