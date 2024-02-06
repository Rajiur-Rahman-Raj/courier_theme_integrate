<?php

namespace App\Traits;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait Upload
{
	public function makeDirectory($path)
	{
		if (file_exists($path)) return true;
		return mkdir($path, 0755, true);
	}

	public function removeFile($path)
	{
		return file_exists($path) && is_file($path) ? @unlink($path) : false;
	}

	public function uploadImage($file, $location, $size = null, $old = null,$filename=null, $thumb = null)
	{
		$path = $this->makeDirectory($location);
		if (!$path) throw new \Exception('File could not been created.');

		if (!empty($old)) {
			$this->removeFile($location . '/' . $old);
			$this->removeFile($location . '/thumb_' . $old);
		}

		if (!$filename){
			$filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
		}
		$image = Image::make($file);

		if (!empty($size)) {
			$size = explode('x', strtolower($size));
			$image->resize($size[0], $size[1]);
		}
		$image->save($location . '/' . $filename);

		if (!empty($thumb)) {
			$thumb = explode('x', $thumb);
			Image::make($file)->resize($thumb[0], $thumb[1])->save($location . '/thumb_' . $filename);
		}
		return $filename;
	}


	public function fileUpload($file, $location, $oldDriver = 'local', $fileName = null, $oldFileName = null, $size = null)
	{

		$activeDisk = config('basic.default_file_driver');

		if (!empty($oldFileName) && Storage::disk($oldDriver)->exists($oldFileName))
			Storage::disk($oldDriver)->delete($oldFileName);

		if (!is_string($file)) {
			$file = new File($file);
			if (in_array(strtolower($file->extension()), ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg', 'avif', 'webp'])) {
				$image = Image::make($file);
				if (!empty($size)) {
					$size = explode('x', strtolower($size));
					$image->resize($size[0], $size[1]);
				}

				$path = $location . '/' . $file->hashName();
				Storage::disk($activeDisk)->put($path, $image->encode());
			} else {
				$path = Storage::disk($activeDisk)->putFileAs($location, $file, $fileName ?? $file->hashName());
			}
		} else {
			Storage::disk($activeDisk)->put($location, $file);
			$path = $location;
		}

		return [
			'path' => $path,
			'driver' => $activeDisk,
		];
	}


	public function fileDelete($driver = 'local', $old)
	{
		if (!empty($old)) {
			if (Storage::disk($driver)->exists($old)) {
				Storage::disk($driver)->delete($old);
			}
		}
		return 0;
	}
}

