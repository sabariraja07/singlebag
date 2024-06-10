<?php

namespace App\Traits;

use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait FileUploadTrait
{

    public function uploadFile(UploadedFile $file, $file_path, $type = 'default', $disk = null)
    {
        if(!isset($file)) return null;

        $disk = $disk ?? config('filesystems.default');
        $path = Storage::disk($disk)->put($file_path, $file, 'public');

        $attachment = new Attachment();
        $attachment->model_type = get_class($this);
        $attachment->model_id = $this->id;
        $attachment->name = $file->getClientOriginalName();
        $attachment->mime_type = $file->getClientMimeType();
        $attachment->type = $type;
        $attachment->disk = $disk;
        $attachment->size = $file->getSize();
        $attachment->path = $path;
        $attachment->responsive_images = null;
        $attachment->status = 1;
        $attachment->shop_id = current_shop_id();
        $attachment->user_id = auth()->id();
        $attachment->save();

        return $path;
    }

    public function getFile($type = 'default')
    {
        $file = Attachment::where('model_type', get_class($this))->where('model_id', $this->id)->where('type', $type)->first();
        return $this->fileResponse($file);
    }

    public function getFiles($type = 'default')
    {
        $files = Attachment::where('model_type', get_class($this))->where('model_id', $this->id)->where('type', $type)->get();
        return $this->filesResponse($files);
    }

    public function filesResponse($files)
    {
        $response = [];
        foreach ($files as $file) {
            $response[] = $this->fileResponse($file);
        }
        return $response;
    }

    public function fileResponse($file) : ?object
    {
        if(!isset($file)) return null;
        return (object) [
            'id' => $file->id,
            'name' => $file->name,
            'url' => $this->getFilePath($file),
            'mime_type' => $file->mime_type,
            'size' => $file->size,
            'responsive_images' => $file->responsive_images,
        ];
    }

    public function deleteFile($type = 'default')
    {
        $attachment = Attachment::where('model_type', get_class($this))->where('model_id', $this->id)->where('type', $type)->first();
        if(isset($attachment)) {
            $attachment->delete();
            Storage::disk($attachment->disk)->delete($attachment->path);
            return true;
        }

        return false;
    }

    public function deleteFileById($id)
    {
        $attachment = Attachment::where('id', $id)->first();
        if(isset($attachment)) {
            $attachment->delete();
            Storage::disk($attachment->disk)->delete($attachment->path);
            return true;
        }
        
        return false;
    }

    public function getFilePath($file)
    {
        if(!isset($file)){
            return asset('uploads/default.png');
        }

        return Storage::disk($file->disk)->url($file->path);
    }

    public function getAttachment($type = 'default')
    {
        return Attachment::where('model_type', get_class($this))->where('model_id', $this->id)->where('type', $type)->first();
    }

    public function getFileSize($type = 'default')
    {
        return Attachment::where('model_type', get_class($this))->where('model_id', $this->id)->where('type', $type)->sum('size');
    }
}
