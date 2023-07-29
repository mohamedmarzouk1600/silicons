<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @FileCreated 12/5/20 5:15 AM
 */

namespace MaxDev\Traits;

use Illuminate\Support\Arr;
use MaxDev\Enums\MethodsUploadImage;
use Illuminate\Http\UploadedFile;
use MaxDev\Models\Clinic;

trait MultiImages
{
    /**
     * Upload Images For Model And Can Delete Old Images.
     * @param $images
     * @param $image_ids
     * @param $model
     * @param $media_collection
     * @param $methodUpload
     * @return void
     */
    public function upload_with_create_orupdate($images, $image_ids, $model, $media_collection, $methodUpload, $image_types)
    {
        if (($image_ids && count($image_ids)) || ($images && count($images))) {
            /**
             * Delete images
             */
            if ($methodUpload == MethodsUploadImage::Update && $image_ids) {
                $media = $model instanceof Clinic ? $model->media : $model->getMedia($media_collection);
                foreach ($media->whereNotIn('id', $image_ids) as $media_to_delete) {
                    $media_to_delete->delete();
                }
            }
            /**
             * Loop through images
             */
            foreach ($images as $key => $image) {
                /**
                 * Upload images
                 */
                if ($image instanceof UploadedFile) {

                    $disk_local = $model instanceof Clinic ? 'public' : 'local';

                    if ($image_types && $image_type = Arr::get($image_types, $key)) {
                        $model->addMedia($image)
                            ->withCustomProperties([
                                'image_type' => $image_type,
                            ])
                            ->toMediaCollection($media_collection, app()->environment('local') ? $disk_local : 's3_assets');
                    } else {
                        $model->addMedia($image)->toMediaCollection($media_collection, app()->environment('local') ? $disk_local : 's3_assets');
                    }
                    /**
                     * Update image type
                     */
                } else {
                    if (($image_id = Arr::get($image_ids, $key)) && $image_types && ($image_type = Arr::get($image_types, $key))) {
                        optional($model->getMedia($media_collection)->where('id', $image_id)->first())
                            ->setCustomProperty('image_type', $image_type)->save();
                    }
                }
            }
        }
    }

    /**
     * Delete Multi Images.
     * @param $model
     * @param $media_collection
     * @return void
     */
    public function delete_multi_images($model, $media_collection)
    {
        foreach ($model->getMedia($media_collection) as $media_to_delete) {
            $media_to_delete->delete();
        }
    }
}
